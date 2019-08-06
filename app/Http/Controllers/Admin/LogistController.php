<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LogistRequest;
use App\Models\Logist;

class LogistController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.logists.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.logists.form');
    }

    /**
     * @param LogistRequest $logistRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LogistRequest $logistRequest)
    {
        $data = $logistRequest->validated();
        $cities = isset($data['cities']) ? $data['cities'] : [];
        unset($data['cities']);
        $logist = Logist::create($data);
        $logist->cities()->sync($cities);

        return redirect()->route('admin.logists.edit', $logist->id)->with(['success' => 'Успешно создан!']);
    }

    /**
     * @param Logist $logist
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Logist $logist)
    {
        return view('admin.logists.form', ['logist' => $logist]);
    }

    /**
     * @param LogistRequest $logistRequest
     * @param Logist $logist
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(LogistRequest $logistRequest, Logist $logist)
    {
        $data = $logistRequest->validated();
        $cities = isset($data['cities']) ? $data['cities'] : [];
        unset($data['cities']);
        $logist->update($data);
        $logist->cities()->sync($cities);

        return redirect()->route('admin.logists.edit', $logist->id)->with(['success' => 'Успешно обновлен!']);
    }

    /**
     * @param Logist $logist
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Logist $logist)
    {
        $logist->delete();

        return response()->json(['message' => 'Пользователь удален']);
    }

    /**
     * @return string
     */
    public function datatable()
    {
        return datatables() ->of(Logist::query())
            ->editColumn('actions', function (Logist $logist) {
                return view('datatable.actions', [
                    'edit' => [
                        'route' => route('admin.logists.edit', $logist->id),
                    ],
                    'delete' => [
                        'id' => $logist->id,
                        'name' => $logist->name,
                        'route' => route('admin.logists.destroy', $logist->id)
                    ]
                ]);
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}