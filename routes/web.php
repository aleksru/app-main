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

    Route::get('/', 'HomeController@index')->name('main.index');

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
    Route::get('orders/{order}/unload/{user}', 'OrderController@unLoad')->name('order.unload');
    Route::get('orders/{order}/onload/{user}', 'OrderController@onLoad')->name('order.onload');

    //заказ с загрузкой реализаций
    Route::post('realizations/{order}', 'OrderController@getOrderWithRealizations')->name('order.realizations');

    Route::get('404', 'HomeController@error404')->name('error');

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
        Route::post('full-report', 'DocumentController@reportFull')->name('report-full');
        Route::get('courier-obligation/{courier}', 'DocumentController@warranty')->name('obligation');
    });

    //Отчеты
    Route::group( ['prefix' => 'reports', 'as' => 'reports.'], function (){
        Route::get('operators', 'ReportController@operators')->name('operators');
        Route::get('days', 'ReportController@days')->name('days');
        Route::get('products', 'ReportController@products')->name('products');
        Route::get('resources', 'ReportController@resources')->name('resources');
        Route::get('reports-table', 'ReportController@operatorsDatatable')->name('datatable');
        Route::get('utm', 'ReportController@utmReport')->name('utmReport');
        Route::get('utm-statuses', 'ReportController@utmStatus')->name('utm_status');
        Route::get('operator-created', 'ReportController@operatorCreatedOrders')->name('operators.created');
        Route::get('operator-report', 'ReportController@reportOperators')->name('operators.orders');
        Route::get('missed-calls-report', 'ReportController@missedCalls')->name('missed_calls');
        Route::get('operator-report-evening', 'ReportController@reportOperatorsEvening')->name('operators.evening');
        Route::get('operator-calls', 'ReportController@operatorsCalls')->name('operators.calls');
    });

    //логи
    Route::group( ['prefix' => 'logs', 'as' => 'logs.'], function (){
        Route::get('order/{order}', 'LogController@order')->name('order');
        Route::get('version', 'Admin\LogController@version')->name('version');
    });

    //Склад
    Route::resource('stock', 'StockController')->only('index');
    Route::get('stock-table', 'StockController@datatable')->name('stock.datatable');

    //Логистика
    Route::group(['prefix' => 'logistics', 'as' => 'logistics.'], function(){
        Route::resource('/', 'LogisticController')->only('index');
        Route::get('logistics-table', 'LogisticController@datatable')->name('datatable');
        Route::get('simple-orders', 'LogisticController@simpleOrders')->name('simple.orders');
        Route::get('simple-orders-datatable', 'LogisticController@simpleOrdersDatatable')->name('simple.orders.datatable');
        Route::post('logist-copy-toggle/', 'LogisticController@logistCopyToggle')->name('copy.toggle');
        Route::get('deliveries', 'LogisticController@deliveries')->name('deliveries');
        Route::post('delivery-toggle', 'LogisticController@deliveryToggle')->name('delivery.toggle');
        Route::get('delivery-widget', 'LogisticController@deliveriesForWidget')->name('deliveries.widget');
    });

    //звонки
    Route::resource('calls', 'ClientCallController')->only('index');
    Route::get('calls-table', 'ClientCallController@datatable')->name('calls.datatable');
    Route::post('callback/{operator}', 'ClientCallController@callback')->name('callback');
    Route::post('calls/get-calls-by-phone', 'ClientCallController@getCallsByPhone')->name('calls.by_phone');
    Route::get('ringing', 'ClientCallController@callQueue')->name('ringing.queue');

    //магазины
    Route::group(['prefix' => 'stores', 'as' => 'stores.'], function(){
        Route::get('get-state-widget', 'StoresController@getStateWidget')->name('state.widget');
    });

    // Уведомления
    Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function(){
        Route::get('user/{user}/unread', 'UserController@unReadNotifications')->name('user');
        Route::get('user/{user}/unread/count', 'UserController@getCountUnReadNotifications')->name('user.unread.count');
        Route::post('set-read', 'NotificationController@setReadNotification')->name('set-read');
    });

    //Sms
    Route::group(['prefix' => 'sms', 'as' => 'sms.'], function(){
        Route::get('distribution', 'SmsController@index')->name('distribution.index');
        Route::post('distribution', 'SmsController@sendDistribution')->name('distribution.send');
        Route::post('client/{client}/send', 'SmsController@sendClient')->name('client.send');
    });

    //Статусы
    Route::group(['prefix' => 'statuses', 'as' => 'statuses.'], function(){
        Route::get('orders', 'StatusController@index')->name('orders');
        Route::post('orders', 'StatusController@massChange')->name('orders.change');
        Route::get('substatuses/{order_status}', 'StatusController@subStatuses')->name('substatuses');
    });

    //Marketing
    Route::group(['prefix' => 'marketing', 'as' => 'marketing.'], function(){
        Route::get('utm_tags', 'MarketingController@utmTags')->name('utm');
        Route::get('utm-tags-table', 'MarketingController@datatable')->name('utm.datatable');
    });

    //Metro
    Route::group(['prefix' => 'metro', 'as' => 'metro.'], function(){
        Route::get('city/{city}', 'MetroController@getMetrosByCity')->name('city');
    });

    //Чаты
    Route::get('chats', 'ChatController@index')->name('chats');
    Route::get('chat/{chat}', 'ChatController@chat')->name('chat.show');
    Route::post('chat/{chat}/message/create', 'ChatMessageController@create')->name('chat.message.create');
    //Route::get('chat/{id}/messages', 'ChatController@messages')->name('chat.messages');
    Route::get('front-chat-datatable', 'ChatController@datatable')->name('front.chat.datatable');
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
    Route::post('stores-hidden-toggle/{store}', 'StoreController@toggleHidden')->name('stores.toggle.hidden');

    //Периоды доставки
    Route::resource('delivery-periods', 'DeliveryPeriodsController')->only('index', 'store', 'destroy');
    Route::delete('otherDelivery/{other_delivery}', 'DeliveryPeriodsController@otherDeliveryDestroy')->name('other-delivery.destroy');
    Route::post('otherDelivery', 'DeliveryPeriodsController@otherDeliveryStore')->name('other-delivery.create');

    //Статусы заказов
    Route::resource('order-statuses', 'OrderStatusesController')->except('show');
    Route::get('order-statuses-table', 'OrderStatusesController@datatable')->name('order-statuses.datatable');
    Route::resource('other-statuses', 'OtherStatusController')->except('show', 'edit', 'update');

    //Поставщики
    Route::resource('suppliers', 'SupplierController')->except('show');
    Route::get('suppliers-table', 'SupplierController@datatable')->name('suppliers.datatable');

    //Операторы
    Route::resource('operators', 'OperatorController')->except('show');
    Route::get('operators-table', 'OperatorController@datatable')->name('operators.datatable');
    Route::post('operators-disable-toggle/{operator}', 'OperatorController@toggleDisable')->name('operators.toggle.disable');

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

    //Товары
    Route::get('products', 'ProductController@index')->name('products.index');
    Route::get('products-table', 'ProductController@datatable')->name('products.datatable');
    Route::post('products-toggle/{product}', 'ProductController@toggleSetType')->name('products.toggle.set-type');

    //Юр лицо
    Route::get('corporate-info', 'CorporateInfoController@index')->name('corporate-info.index');
    Route::post('corporate-info', 'CorporateInfoController@store')->name('corporate-info.store');

    //Магазины апи
    Route::group( ['prefix' => 'remote-store', 'as' => 'remote-store.', 'namespace' => 'Stores'], function (){
        Route::get('update-prices/{store}', 'RemoteStoresController@runUpdatePrices')->name('update-prices');
        Route::get('get-state_store/{store}', 'RemoteStoresController@getStateStore')->name('state');
    });

    //Города
    Route::resource('cities', 'CityController')->except('show');
    Route::get('cities-table', 'CityController@datatable')->name('cities.datatable');

    //Чаты
    Route::resource('chats', 'ChatController')->except('show');
    Route::get('chats-datatable', 'ChatController@datatable')->name('chats.datatable');
});