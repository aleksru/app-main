<?php


namespace App\Http\Controllers;


use App\Models\Supplier;

class SupplierController extends Controller
{
    public function get(Supplier $supplier = null)
    {
        if($supplier){
            return response()->json($supplier);
        }

        return response()->json(Supplier::all());
    }
}