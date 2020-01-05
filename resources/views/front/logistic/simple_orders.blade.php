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
                'nodata' => [
                    'name' => '',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'created_at' => [
                    'name' => 'Дата',
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
                'product_name' => [
                    'name' => 'Модель',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'imei' => [
                    'name' => 'IMEI',
                    'width' => '1%',
                    'searchable' => false,
                ],
               'quantity' => [
                    'name' => 'Кол-во',
                    'width' => '1%',
                    'searchable' => false,
                ],
               'price_opt' => [
                    'name' => 'Закупка',
                    'width' => '1%',
                    'searchable' => false,
                ],
               'price' => [
                    'name' => 'Продажа',
                    'width' => '1%',
                    'searchable' => false,
                ],
               'courier_payment' => [
                    'name' => 'ЗП курьера',
                    'width' => '1%',
                    'searchable' => false,
                ],
               'profit' => [
                    'name' => 'Прибыль',
                    'width' => '1%',
                    'searchable' => false,
                ],
               'courier_name' => [
                    'name' => 'Курьер',
                    'width' => '1%',
                    'searchable' => false,
                ],
               'supplier' => [
                    'name' => 'Поставщик',
                    'width' => '1%',
                    'searchable' => false,
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

            $('#orders-table').on( 'draw.dt', function () {
                $('#orders-table tr').dblclick(function(e){
                    let range = document.createRange();
                    let sel = window.getSelection();
                    range.setStart(this.children[0], 0);
                    range.setEnd(this.children[this.children.length - 1], 0);
                    sel.removeAllRanges();
                    sel.addRange(range);

                    try {
                        document.execCommand('copy');
                        if(this.dataset.productid == '') {
                            throw 'product_is_null';
                        }
                        axios.post("{!! route('logistics.copy.toggle') !!}", {
                            realization_id: this.dataset.realizationid,
                            row: this.innerHTML,
                        }).then((res) => {
                            this.classList.remove('alert-danger');
                            this.classList.add('alert-success');
                            toast[res.data.type]('', {title: res.data.message});
                        });

                    } catch(err) {
                        let mess = 'Произошла ошибка!';
                        if(err === 'product_is_null') {
                            mess += ' Отсутстуют товары';
                        }
                        toast.error(mess);
                        console.log('Error copy to buffer');
                    }
                    sel.removeAllRanges();
                });
            });
        });

    </script>
@endpush