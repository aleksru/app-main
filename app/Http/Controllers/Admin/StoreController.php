<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRequest;
use App\Store;

class StoreController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.store.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.store.form');
    }

    /**
     * @param StoreRequest $storeRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $storeRequest)
    {
        return redirect()->route('admin.stores.edit', Store::create($storeRequest->validated())->id)->with(['success' => 'Успешно создан!']);
    }

    /**
     * @param Store $store
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Store $store)
    {
        return view('admin.store.form', [ 'store' => $store ]);
    }

    /**
     * @param Store $store
     * @param StoreRequest $storeRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Store $store, StoreRequest $storeRequest)
    {
        $store->update($storeRequest->validated());

        return redirect()->route('admin.stores.edit', $store->id)->with(['success' => 'Успешно обновлен!']);
    }

    /**
     * @param Store $store
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Store $store)
    {
        $store->delete();

        return response()->json(['message' => 'Пользователь удален']);
    }


    /**
     * @return json
     */
    public function datatable()
    {
        return datatables() ->of(Store::query())
            ->editColumn('actions', function (Store $store) {
                return view('datatable.actions', [
                    'edit' => [
                        'route' => route('admin.stores.edit', $store->id),
                    ],
                    'delete' => [
                        'id' => $store->id,
                        'name' => $store->name,
                        'route' => route('admin.stores.destroy', $store->id)
                    ]
                ]);
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

}