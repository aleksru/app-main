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
        return view('welcome');
    })->name('main.index');

    //загрузка прайса
    Route::get('product', 'ProductController@index')->name('product.index');
    Route::post('product', 'ProductController@uploadPrice')->name('upload-price');
    Route::post('product-search', 'ProductController@search')->name('product.search');
    Route::post('product-create', 'ProductController@create')->name('product.create');


    //заказы
    Route::resource('orders', 'OrderController');
    Route::get('orders-table', 'OrderController@datatable')->name('orders.datatable');
    Route::post('product-orders', 'OrderController@updateProductsOrder')->name('update.product-orders');


    //Route::get('/home', 'HomeController@index')->name('home');
    Route::get('404', function(){
        return 404;
    })->name('error');

    //клиенты
    Route::resource('clients', 'ClientController');
    Route::post('create-client-order', 'ClientController@createOrderClient')->name('create.user-order');

    //документы
    Route::group( ['prefix' => 'docs', 'as' => 'docs.'], function (){
        Route::get('market-check/{order}', 'DocumentController@getMarketCheck')->name('market-check');
        Route::get('route-map/{courier}', 'DocumentController@getRouteMap')->name('route-map');
        Route::get('print-form/', 'DocumentController@index')->name('index');
        Route::post('print-form/', 'DocumentController@form')->name('form');
    });

});

//Админка
Route::group(['middleware' =>['auth',  'role:admin'], 'prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin.'], function() {
    //Пользователи
    Route::resource('users', 'UsersController');
    Route::get('users-table', 'UsersController@datatable')->name('users.datatable');

    //Лог админки
    Route::get('logs', 'LogController@index')->name('logs.index');

    //Магазины
    Route::resource('stores', 'StoreController')->except('show');
    Route::get('stores-table', 'StoreController@datatable')->name('stores.datatable');

    //Периоды доставки
    Route::resource('delivery-periods', 'DeliveryPeriodsController')->only('index', 'store', 'destroy');

    //Статусы заказов
    Route::resource('order-statuses', 'OrderStatusesController')->except('show');
    Route::get('order-statuses-table', 'OrderStatusesController@datatable')->name('order-statuses.datatable');

    //Поставщики
    Route::resource('suppliers', 'SupplierController')->except('show');
    Route::get('suppliers-table', 'SupplierController@datatable')->name('suppliers.datatable');

    //Операторы
    Route::resource('operators', 'OperatorController')->except('show');
    Route::get('operators-table', 'OperatorController@datatable')->name('operators.datatable');

    //Курьеры
    Route::resource('couriers', 'CourierController')->except('show');
    Route::get('couriers-table', 'CourierController@datatable')->name('couriers.datatable');

    //Прайс-листы
    Route::resource('price-types', 'PriceTypeController')->only('index', 'store', 'destroy');
});