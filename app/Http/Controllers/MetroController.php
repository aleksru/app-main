<?php


namespace App\Http\Controllers;


use App\Models\City;

class MetroController extends Controller
{
    /**
     * @param City $city
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMetrosByCity(City $city)
    {
        return response()->json([
            'metros' => $city->metros()
                ->selectRaw("id, CONCAT(IFNULL(line, ''), '/', name) as name")
                ->orderBy('name')
                ->get()
        ]);
    }
}