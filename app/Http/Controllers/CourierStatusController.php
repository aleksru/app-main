<?php


namespace App\Http\Controllers;


use App\Http\Requests\CourierStatusRequest;
use App\Models\CourierStatus;

class CourierStatusController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.courier_statuses.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.courier_statuses.form');
    }

    /**
     * @param CourierStatusRequest $courierStatusRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CourierStatusRequest $courierStatusRequest)
    {
        $courierStatus = CourierStatus::create($courierStatusRequest->validated());
        return redirect()->route('courier-statuses.edit', $courierStatus->id)->with(['success' => 'Успешно создан!']);
    }

    /**
     * @param CourierStatus $courierStatus
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(CourierStatus $courierStatus)
    {
        return view('admin.courier_statuses.form', ['courierStatus' => $courierStatus]);
    }

    /**
     * @param CourierStatusRequest $courierStatusRequest
     * @param CourierStatus $courierStatus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CourierStatusRequest $courierStatusRequest, CourierStatus $courierStatus)
    {
        $courierStatus->update($courierStatusRequest->validated());
        return redirect()->route('courier-statuses.edit', $courierStatus->id)->with(['success' => 'Успешно обновлен!']);
    }

    /**
     * @param CourierStatus $courierStatus
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(CourierStatus $courierStatus)
    {
        $courierStatus->delete();

        return response()->json(['message' => 'Успешно удален']);
    }

    /**
     * @return json
     */
    public function datatable()
    {
        return datatables() ->of(CourierStatus::query())
            ->editColumn('actions', function (CourierStatus $courierStatus) {
                return view('datatable.actions', [
                    'edit' => [
                        'route' => route('courier-statuses.edit', $courierStatus->id),
                    ],
                    'delete' => [
                        'id' => $courierStatus->id,
                        'name' => $courierStatus->name,
                        'route' => route('courier-statuses.destroy', $courierStatus->id)
                    ]
                ]);
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}