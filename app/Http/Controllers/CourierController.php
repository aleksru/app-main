<?php


namespace App\Http\Controllers;



use App\Models\Courier;

class CourierController extends Controller
{
    public function get(Courier $courier = null)
    {
        if($courier){
            return response()->json($courier);
        }

        return response()->json(Courier::all());
    }
}