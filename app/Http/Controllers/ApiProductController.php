<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class ApiProductController extends Controller
{

    public function index()
    {
        //dump(Product::all(), storage_path().'\app\public\docs\\pr.xml');
        //debug(file_get_contents(storage_path().'\app\public\docs\\pr.xml'));
        //$xml = simplexml_load_string(file_get_contents(storage_path().'\app\public\docs\\pr.xml'), "SimpleXMLElement", LIBXML_NOCDATA);
        //$json = json_encode($xml);
        //$array = json_decode($json,TRUE);
        //dump($this->excelToArray(storage_path().'\app\public\docs\\pr.xlsx'));
        return 1;
    }

    public function api(Request $req)
    {
        $count = 10;
        $skip = 0;
        
        empty($req->get('count')) ?: $count = $req->get('count');
        
        if ($req->get('page') && $req->get('page') > 0) {
            $skip  = $count * $req->get('page');
        }
        
        return Product::take($count)->skip($skip)->get();
    }
    
    public function getDataApi()
    {
//        $data = file_get_contents("http://ocart/lib/public/api/get-product?key=d345dskfjsdk3432DFFkfgkKSDFjfjm32400sdg324dsfsdfsdf&price=123&count=10&page=2");
//        dump(json_decode($data, true));
        $data = new \App\API\ProductApiSave();
        (new \App\API\ApiGetProduct($data))->getDataApi();
        dump($data);
        
        return 1;
    }
    
}
