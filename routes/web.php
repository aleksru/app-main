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

    //прайсы
    Route::resource('price-lists', 'PriceListController')->only(['index', 'show']);
    Route::get('price-lists-datatable', 'PriceListController@datatable')->name('price-lists.datatable');
    Route::get('price-lists-datatable/{price_list}', 'PriceListController@showDatatable')->name('price-lists.show.datatable');

    //заказы
    Route::resource('orders', 'OrderController');
    Route::get('orders-table', 'OrderController@datatable')->name('orders.datatable');
    Route::post('product-orders/{order}', 'OrderController@updateProductsOrder')->name('update.product-orders');
    Route::post('orders/{order}/set-status', 'OrderController@updateStatus')->name('orders.set-status');
    Route::post('orders/{order}/comment-logist', 'OrderController@commentLogist')->name('orders.comment-logist');

    //заказ с загрузкой реализаций
    Route::post('realizations/{order}', 'OrderController@getOrderWithRealizations')->name('order.realizations');


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
        Route::post('report', 'DocumentController@reportDayOrders')->name('report');
        Route::get('print-form/', 'DocumentController@index')->name('index');
        Route::post('print-form/', 'DocumentController@form')->name('form');
    });

    //логи
    Route::group( ['prefix' => 'logs', 'as' => 'logs.'], function (){
        Route::get('order/{order}', 'LogController@order')->name('order');
    });

    //Склад
    Route::resource('stock', 'StockController')->only('index');
    Route::get('stock-table', 'StockController@datatable')->name('stock.datatable');

    //Логистика
    Route::resource('logistics', 'LogisticController')->only('index');
    Route::get('logistics-table', 'LogisticController@datatable')->name('logistics.datatable');

    //звонки
    Route::resource('calls', 'ClientCallController')->only('index');
    Route::get('calls-table', 'ClientCallController@datatable')->name('calls.datatable');

    //statistics
    Route::get('statistics', 'Statistics\StatisticController@index')->name('statistic.index');
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

    //Причины отказа
    Route::resource('denial-reasons', 'DenialReasonController')->only('index', 'store', 'destroy');

    //Типы доставки
    Route::resource('delivery-types', 'DeliveryTypeController')->only('index', 'store', 'destroy');

    //Склад
    Route::resource('stock', 'StockUserController')->except('show');
    Route::get('stock-table', 'StockUserController@datatable')->name('stock.datatable');

    //Логисты
    Route::resource('logists', 'LogistController')->except('show');
    Route::get('logists-table', 'LogistController@datatable')->name('logists.datatable');
});