<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OperatorRequest;
use App\Models\Operator;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.operators.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.operators.form');
    }

    /**
     * @param OperatorRequest $operatorRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(OperatorRequest $operatorRequest)
    {
        return redirect()->route('admin.operators.edit', Operator::create($operatorRequest->validated())->id)->with(['success' => 'Успешно создан!']);
    }

    /**
     * @param Operator $operator
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Operator $operator, Request $request)
    {
        $isActiveJobTimeNav = (bool)$request->get('page');
        $jobTime = $operator->user ? $operator->user->controlTimes()->orderBy('logon', 'desc')->paginate(15) : null;

        return view('admin.operators.form', compact('operator', 'jobTime', 'isActiveJobTimeNav'));
    }

    /**
     * @param OperatorRequest $operatorRequest
     * @param Operator $operator
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(OperatorRequest $operatorRequest, Operator $operator)
    {
        $operator->update($operatorRequest->validated());

        return redirect()->route('admin.operators.edit', $operator->id)->with(['success' => 'Успешно обновлен!']);
    }

    /**
     * @param Operator $operator
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Operator $operator)
    {
        $operator->delete();

        return response()->json(['message' => 'Оператор удален']);
    }

    /**
     * @return json
     */
    public function datatable()
    {
        return datatables() ->of(Operator::query())
            ->editColumn('actions', function (Operator $operator) {
                return view('datatable.actions', [
                    'edit' => [
                        'route' => route('admin.operators.edit', $operator->id),
                    ],
                    'delete' => [
                        'id' => $operator->id,
                        'name' => $operator->name,
                        'route' => route('admin.operators.destroy', $operator->id)
                    ]
                ]);
            })
            ->editColumn('is_disabled', function (Operator $operator) {
                return view('datatable.toggle', [
                    'route' => route('admin.operators.toggle.disable', $operator->id),
                    'id' => $operator->id,
                    'check' => $operator->is_disabled
                ]);
            })
            ->rawColumns(['actions', 'is_disabled'])
            ->make(true);
    }

    /**
     * @param Operator $operator
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleDisable(Operator $operator)
    {
        $operator->is_disabled = ! $operator->is_disabled;
        $operator->save();

        return response()->json(['message' => 'Успешно обновлен!']);
    }

}
