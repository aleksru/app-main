<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Auth::routes();
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['middleware' =>'auth'], function() {
    Route::get('/', function () {
        return redirect()->route('orders.index');
    });

    Route::get('product', 'ProductController@index')->name('product.index');

    Route::post('product', 'ProductController@uploadPrice')->name('upload-price');

    Route::resource('orders', 'OrderController');

    Route::get('orders-table', 'OrderController@datatable')->name('orders.datatable');

    //Route::get('/home', 'HomeController@index')->name('home');
    Route::get('404', function(){
        return 404;
    })->name('error');
});