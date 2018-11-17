<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.suppliers.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.suppliers.form');
    }

    /**
     * @param SupplierRequest $supplierRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SupplierRequest $supplierRequest)
    {
        return redirect()->route('admin.suppliers.edit', Supplier::create($supplierRequest->validated())->id)->with(['success' => 'Успешно создан!']);
    }

    /**
     * @param Supplier $supplier
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.form', ['supplier' => $supplier]);
    }

    /**
     * @param SupplierRequest $supplierRequest
     * @param Supplier $supplier
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SupplierRequest $supplierRequest, Supplier $supplier)
    {
        $supplier->update($supplierRequest->validated());

        return redirect()->route('admin.suppliers.edit', $supplier->id)->with(['success' => 'Успешно обновлен!']);
    }

    /**
     * @param Supplier $supplier
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return response()->json(['message' => 'Пользователь удален']);
    }

    /**
     * @return json
     */
    public function datatable()
    {
        return datatables() ->of(Supplier::query())
            ->editColumn('actions', function (Supplier $supplier) {
                return view('datatable.actions', [
                    'edit' => [
                        'route' => route('admin.suppliers.edit', $supplier->id),
                    ],
                    'delete' => [
                        'id' => $supplier->id,
                        'name' => $supplier->name,
                        'route' => route('admin.suppliers.destroy', $supplier->id)
                    ]
                ]);
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
