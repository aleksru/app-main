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

Route::post('events/call', 'ApiMangoController@index');

Route::post('events/summary', 'ApiMangoController@summary');

Route::group(['prefix' => 'result', 'as' => 'mango.result'], function (){
    Route::post('callback', 'ApiMangoController@resultCallback')->name('callback');
});

Route::group(['middleware' =>'api.v2', 'prefix' => 'v2', 'as' => 'apiV2.', 'namespace' => 'Api'], function (){
    Route::get('products', 'ProductController@products')->name('products.get');
    Route::get('price-list/version', 'PriceListController@version')->name('price-list.version');
    Route::post('order', 'OrderController@create')->name('order.create');
    Route::get('store/price-list', 'StoreController@getPriceList')->name('store.pricelist');
    Route::post('store/set-online', 'StoreController@onlineStore')->name('store.set_online');
});