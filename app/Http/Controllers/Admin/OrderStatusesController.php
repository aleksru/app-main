<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderStatusRequest;
use App\Models\OrderStatus;
use App\Models\OtherStatus;
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
        $otherStatuses = OtherStatus::query()->typeSubStatuses()->select('id', 'name as text')->get();

        return view('admin.statuses.form', compact('otherStatuses'));
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
        $orderStatus->load('subStatuses');
        $otherStatuses = OtherStatus::query()->typeSubStatuses()->select('id', 'name as text')->get()->toArray();
        foreach ($otherStatuses as $key => $otherStatus){
            $checkStatus = $orderStatus->subStatuses->first(function ($value, $key) use (&$otherStatus) {
                return $value->id == $otherStatus['id'];
            });
            if($checkStatus){
                $otherStatuses[$key]["selected"] = true;
            }
        }

        return view('admin.statuses.form' , ['status' => $orderStatus, 'otherStatuses' => $otherStatuses]);
    }

    /**
     * @param OrderStatusRequest $statusRequest
     * @param OrderStatus $orderStatus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(OrderStatusRequest $statusRequest, OrderStatus $orderStatus)
    {
        $orderStatus->update($statusRequest->validated());
        $orderStatus->subStatuses()->sync(array_filter($statusRequest->get('sub_statuses_ids')));

        return redirect()->route('admin.order-statuses.edit', $orderStatus->id)->with(['success' => 'Успешно обновлен!']);
    }

    /**
     * @param OrderStatus $orderStatus
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(OrderStatus $orderStatus)
    {
        $orderStatus->delete();

        return response()->json(['message' => 'Статус удален']);
    }

    /**
     * @return json
     */
    public function datatable()
    {
        return datatables() ->of(OrderStatus::query())
            ->editColumn('color', function (OrderStatus $orderStatus) {
                return
                    '<h4><span class="bg-'.$orderStatus->color.'">'.$orderStatus->color.'</span></h4>';
            })
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
            ->rawColumns(['actions', 'color'])
            ->make(true);
    }
}
