<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StockUserRequest;
use App\Models\StockUser;

class StockUserController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.stock_users.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.stock_users.form');
    }

    /**
     * @param StockUserRequest $stockUserRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StockUserRequest $stockUserRequest)
    {
        return redirect()->route('admin.stock.edit', StockUser::create($stockUserRequest->validated())->id)->with(['success' => 'Успешно создан!']);
    }

    /**
     * @param StockUser $stockUser
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(StockUser $stock)
    {
        return view('admin.stock_users.form', ['stockUser' => $stock]);
    }

    /**
     * @param StockUserRequest $stockUserRequest
     * @param StockUser $stockUser
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StockUserRequest $stockUserRequest, StockUser $stock)
    {
        $stock->update($stockUserRequest->validated());

        return redirect()->route('admin.stock.edit', $stock->id)->with(['success' => 'Успешно обновлен!']);
    }

    /**
     * @param StockUser $stockUser
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(StockUser $stock)
    {
        $stock->delete();

        return response()->json(['message' => 'Пользователь удален']);
    }

    /**
     * @return string
     */
    public function datatable()
    {
        return datatables() ->of(StockUser::query())
            ->editColumn('actions', function (StockUser $stockUser) {
                return view('datatable.actions', [
                    'edit' => [
                        'route' => route('admin.stock.edit', $stockUser->id),
                    ],
                    'delete' => [
                        'id' => $stockUser->id,
                        'name' => $stockUser->name,
                        'route' => route('admin.stock.destroy', $stockUser->id)
                    ]
                ]);
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}