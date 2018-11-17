<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderStatusRequest;
use App\Models\OrderStatus;
use Illuminate\Http\Request;

class OrderStatusesController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.statuses.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.statuses.form');
    }

    /**
     * @param OrderStatusRequest $statusRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(OrderStatusRequest $statusRequest)
    {
        return redirect()->route('admin.order-statuses.edit', OrderStatus::create($statusRequest->validated())->id)->with(['success' => 'Успешно создан!']);
    }


    /**
     * @param OrderStatus $orderStatus
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(OrderStatus $orderStatus)
    {
        return view('admin.statuses.form' , ['status' => $orderStatus]);
    }

    /**
     * @param OrderStatusRequest $statusRequest
     * @param OrderStatus $orderStatus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(OrderStatusRequest $statusRequest, OrderStatus $orderStatus)
    {
        $orderStatus->update($statusRequest->validated());

        return redirect()->route('admin.order-statuses.edit', $orderStatus->id)->with(['success' => 'Успешно обновлен!']);
    }

    /**
     * @param OrderStatus $orderStatus
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(OrderStatus $orderStatus)
    {
        $orderStatus->delete();

        return response()->json(['message' => 'Пользователь удален']);
    }

    /**
     * @return json
     */
    public function datatable()
    {
        return datatables() ->of(OrderStatus::query())
            ->editColumn('actions', function (OrderStatus $orderStatus) {
                return view('datatable.actions', [
                    'edit' => [
                        'route' => route('admin.order-statuses.edit', $orderStatus->id),
                    ],
                    'delete' => [
                        'id' => $orderStatus->id,
                        'name' => $orderStatus->status,
                        'route' => route('admin.order-statuses.destroy', $orderStatus->id)
                    ]
                ]);
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
