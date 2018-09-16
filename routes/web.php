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

Route::get('/', function () {
    return view('welcome');
});

Route::get('product', 'ProductController@index')->name('product.index');

Route::post('product', 'ProductController@uploadPrice')->name('upload-price');

Route::resource('orders', 'OrderController');

Route::get('orders-table', 'OrderController@datatable')->name('orders.datatable');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
