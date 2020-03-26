<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourierRequest;
use App\Models\Courier;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.couriers.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.couriers.form');
    }

    /**
     * @param CourierRequest $courierRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CourierRequest $courierRequest)
    {
        return redirect()->route('couriers.edit', Courier::create($courierRequest->validated())->id)->with(['success' => 'Успешно создан!']);
    }

    /**
     * @param Courier $courier
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Courier $courier)
    {
        return view('admin.couriers.form', ['courier' => $courier]);
    }

    /**
     * @param CourierRequest $courierRequest
     * @param Courier $courier
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CourierRequest $courierRequest, Courier $courier)
    {
        $courier->update($courierRequest->validated());

        return redirect()->route('couriers.edit', $courier->id)->with(['success' => 'Успешно обновлен!']);
    }

    /**
     * @param Courier $courier
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Courier $courier)
    {
        $courier->delete();

        return response()->json(['message' => 'Курьер удален']);
    }

    /**
     * @return json
     */
    public function datatable()
    {
        return datatables() ->of(Courier::query())
            ->editColumn('actions', function (Courier $courier) {
                return view('datatable.actions', [
                    'edit' => [
                        'route' => route('couriers.edit', $courier->id),
                    ],
                    'delete' => [
                        'id' => $courier->id,
                        'name' => $courier->name,
                        'route' => route('couriers.destroy', $courier->id)
                    ]
                ]);
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
