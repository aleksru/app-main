@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Таблица заказов Логистика
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>
    </section>
@endsection
@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="alert alert-warning">
        <ul>
            <li>Копирование - двойной клик по строке копирует всю строку</li>
        </ul>
    </div>
    <div class="box-header">
        <a href="{{route('orders.create')}}" target="_blank"><button class="btn btn-sm btn-primary pull-right">
                <i class="fa fa-plus"></i> Новый заказ
            </button></a>
    </div>
    <div class="col-md-12">
        @include('datatable.datatable',[
            'id' => 'orders-table',
            'route' => $routeDatatable ?? route('orders.datatable'),
            'ordering' => false,
            'columns' => [
                'created_at' => [
                    'name' => 'Дата',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'date_delivery' => [
                    'name' => 'Дата доставки',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'operator' => [
                    'name' => 'Оператор',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'store' => [
                    'name' => 'Источник',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'id' => [
                    'name' => 'Номер заказа',
                    'width' => '1%',
                    'searchable' => true,
                ],
                'real_denied' => [
                    'name' => 'Реал.прич. отказа',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'comment' => [
                    'name' => 'Коммент КЦ',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'comment_stock' => [
                    'name' => 'Коммент СКЛАД',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'comment_logist' => [
                    'name' => 'Коммент ЛОГ',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'status' => [
                    'name' => 'Статус',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'name_customer' => [
                    'name' => 'ФИО клиента',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'delivery_time' => [
                    'name' => 'Время доставки',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'address' => [
                    'name' => 'Адрес',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'client_phone' => [
                    'name' => 'Телефон',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'products' => [
                    'name' => 'Товары',
                    'width' => '1%',
                    'searchable' => false,
                ],
               'courier_payment' => [
                    'name' => 'ЗП курьера',
                    'width' => '1%',
                    'searchable' => false,
                ],
               'courier_name' => [
                    'name' => 'Курьер',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'btn_details' => [
                    'name' => '-',
                    'width' => '1%',
                    'searchable' => false,
                ],
            ],
        ])
        <fast-order :table-id="'orders-table'"></fast-order>
    </div>
@endsection

@push('scripts')
    <script>
        /**
         *обновление таблицы
         */
        $(function () {
    //        setInterval( function () {
    //            $('#orders-table').DataTable().ajax.reload(null, false);
    //        }, 7000 );
            window.Echo.channel('order')
                .listen('OrderConfirmedEvent', (e) => {
                    $('#orders-table').DataTable().ajax.reload(null, false);
                })
                .listen('RealizationCopyLogistEvent', (e) => {
                    let row = $('tr[data-realizationid="' + e.realization.id + '"]');
                    row.removeClass('alert-danger').addClass('alert-success');
                })
                .listen('UpdateRealizationsConfirmedOrderEvent', (e) => {
                    $('#orders-table').DataTable().ajax.reload(null, false);
                });
        });

    </script>
@endpush