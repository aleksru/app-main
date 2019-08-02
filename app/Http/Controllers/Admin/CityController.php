<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CityRequest;
use App\Models\City;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.cities.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.cities.form');
    }

    /**
     * @param CityRequest $cityRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CityRequest $cityRequest)
    {
        $city = City::create($cityRequest->validated());

        return redirect()->route('admin.cities.edit', $city->id)->with(['success' => 'Успешно создан!']);
    }

    /**
     * @param City $city
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(City $city)
    {
        return view('admin.cities.form', compact('city'));
    }

    /**
     * @param CityRequest $cityRequest
     * @param City $city
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CityRequest $cityRequest, City $city)
    {
        $city->update($cityRequest->validated());

        return redirect()->route('admin.cities.edit', $city->id)->with(['success' => 'Успешно изменен!']);
    }

    /**
     * @param City $city
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(City $city)
    {
        $city->delete();

        return response()->json(['message' => 'Город удален']);
    }

    /**
     * Datatable
     * @return string
     */
    public function datatable()
    {
        return datatables() ->of(City::query())
            ->editColumn('actions', function (City $city) {
                return view('datatable.actions', [
                    'edit' => [
                        'route' => route('admin.cities.edit', $city->id),
                    ],
                    'delete' => [
                        'id' => $city->id,
                        'name' => $city->name,
                        'route' => route('admin.cities.destroy', $city->id)
                    ]
                ]);
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
