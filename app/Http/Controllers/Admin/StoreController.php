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

        return response()->json(['message' => 'Магазин удален']);
    }


    /**
     * @return json
     */
    public function datatable()
    {
        return datatables()
            ->of(
                Store::query()->selectRaw('stores.*, price_types.name as price_list')
                    ->leftJoin('price_types', 'stores.price_type_id', '=', 'price_types.id'))
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
            ->editColumn('is_hidden', function (Store $store) {
                return view('datatable.toggle', [
                    'route' => route('admin.stores.toggle.hidden', $store->id),
                    'id' => $store->id,
                    'check' => $store->is_hidden
                ]);
            })
            ->editColumn('is_disable_api_price', function (Store $store) {
                return $store->is_disable_api_price ? 'НЕТ' : 'ДА';
            })
            ->editColumn('last_request_prices', function (Store $store) {
                return $store->last_request_prices ? $store->last_request_prices->format('d.m.Y H:i:s'): null;
            })
            ->editColumn('price_list', function (Store $store) {
                return $store->priceType ? $store->priceType->name : null;
            })
            ->rawColumns(['actions', 'is_hidden'])
            ->make(true);
    }

    /**
     * @param Store $store
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleHidden(Store $store)
    {
        $store->is_hidden = ! $store->is_hidden;
        $store->save();

        return response()->json(['message' => 'Успешно обновлен!']);
    }

}
