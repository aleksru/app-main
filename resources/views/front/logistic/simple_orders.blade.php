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
            'columns' => [
                'product.date' => [
                    'name' => 'Дата',
                    'width' => '1%',
                    'searchable' => true,
                ],
                'product.operator' => [
                    'name' => 'Оператор',
                    'width' => '1%',
                    'searchable' => true,
                ],
                'product.store' => [
                    'name' => 'Источник',
                    'width' => '1%',
                    'searchable' => true,
                ],
                'product.order' => [
                    'name' => 'Номер заказа',
                    'width' => '1%',
                    'searchable' => true,
                ],
                'product.real_denied' => [
                    'name' => 'Реал.прич. отказа',
                    'width' => '1%',
                    'searchable' => true,
                ],
                'product.type' => [
                    'name' => 'Коммент КЦ',
                    'width' => '1%',
                    'searchable' => true,
                ],
                'product.comment_logist' => [
                    'name' => 'Коммент ЛОГ',
                    'width' => '1%',
                    'searchable' => true,
                ],
                'product.status' => [
                    'name' => 'Статус',
                    'width' => '1%',
                    'searchable' => true,
                ],
                'product.client_name' => [
                    'name' => 'ФИО клиента',
                    'width' => '1%',
                    'searchable' => true,
                ],
                'product.delivery_time' => [
                    'name' => 'Время доставки',
                    'width' => '1%',
                    'searchable' => true,
                ],
                'product.address' => [
                    'name' => 'Адрес',
                    'width' => '1%',
                    'searchable' => true,
                ],
                'product.client_phone' => [
                    'name' => 'Телефон',
                    'width' => '1%',
                    'searchable' => true,
                ],
                'product.name' => [
                    'name' => 'Модель',
                    'width' => '1%',
                    'searchable' => true,
                ],
                'product.imei' => [
                    'name' => 'IMEI',
                    'width' => '1%',
                    'searchable' => true,
                ],
               'product.quantity' => [
                    'name' => 'Кол-во',
                    'width' => '1%',
                    'searchable' => true,
                ],
               'product.price_opt' => [
                    'name' => 'Закупка',
                    'width' => '1%',
                    'searchable' => true,
                ],
               'product.price' => [
                    'name' => 'Продажа',
                    'width' => '1%',
                    'searchable' => true,
                ],
               'product.courier_payment' => [
                    'name' => 'ЗП курьера',
                    'width' => '1%',
                    'searchable' => true,
                ],
               'product.profit' => [
                    'name' => 'Прибыль',
                    'width' => '1%',
                    'searchable' => true,
                ],
               'product.courier_name' => [
                    'name' => 'Курьер',
                    'width' => '1%',
                    'searchable' => true,
                ],
               'product.supplier' => [
                    'name' => 'Поставщик',
                    'width' => '1%',
                    'searchable' => true,
                ],
            ],
        ])
    </div>
@endsection

@push('scripts')
<script>
    /**
     *обновление таблицы
     */
    $(function () {
        setInterval( function () {
            $('#orders-table').DataTable().ajax.reload(null, false);
        }, 5000 );

        $('#orders-table').on( 'draw.dt', function () {
            $('#orders-table tr').dblclick(function(e){
                let range = document.createRange();
                let sel = window.getSelection();

                range.selectNode(this);
                sel.removeAllRanges();
                sel.addRange(range);
                window.getSelection().addRange(range);
                //пытаемся скопировать текст в буфер обмена
                try {
                    document.execCommand('copy');
                    toast.success('', {title: 'Скопировано'});
                } catch(err) {
                    console.log('Error copy to buffer');
                }
                sel.removeAllRanges();
            });
        });
    });

</script>
@endpush