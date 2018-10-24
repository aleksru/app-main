<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/get-product/', 'ApiProductController@api')->middleware('api.key');

Route::get('/price-version/', 'ApiProductController@priceVersion')->middleware('api.key');

Route::post('set-order', 'ApiOrdersController@api')->middleware('api.key');