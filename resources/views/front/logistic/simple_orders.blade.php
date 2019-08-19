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
                'product.nodata' => [
                    'name' => '',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'product.date' => [
                    'name' => 'Дата',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'product.operator' => [
                    'name' => 'Оператор',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'product.store' => [
                    'name' => 'Источник',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'product.order' => [
                    'name' => 'Номер заказа',
                    'width' => '1%',
                    'searchable' => true,
                ],
                'product.real_denied' => [
                    'name' => 'Реал.прич. отказа',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'product.type' => [
                    'name' => 'Коммент КЦ',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'product.comment_logist' => [
                    'name' => 'Коммент ЛОГ',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'product.status' => [
                    'name' => 'Статус',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'product.client_name' => [
                    'name' => 'ФИО клиента',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'product.delivery_time' => [
                    'name' => 'Время доставки',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'product.address' => [
                    'name' => 'Адрес',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'product.client_phone' => [
                    'name' => 'Телефон',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'product.name' => [
                    'name' => 'Модель',
                    'width' => '1%',
                    'searchable' => false,
                ],
                'product.imei' => [
                    'name' => 'IMEI',
                    'width' => '1%',
                    'searchable' => false,
                ],
               'product.quantity' => [
                    'name' => 'Кол-во',
                    'width' => '1%',
                    'searchable' => false,
                ],
               'product.price_opt' => [
                    'name' => 'Закупка',
                    'width' => '1%',
                    'searchable' => false,
                ],
               'product.price' => [
                    'name' => 'Продажа',
                    'width' => '1%',
                    'searchable' => false,
                ],
               'product.courier_payment' => [
                    'name' => 'ЗП курьера',
                    'width' => '1%',
                    'searchable' => false,
                ],
               'product.profit' => [
                    'name' => 'Прибыль',
                    'width' => '1%',
                    'searchable' => false,
                ],
               'product.courier_name' => [
                    'name' => 'Курьер',
                    'width' => '1%',
                    'searchable' => false,
                ],
               'product.supplier' => [
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
//        }, 5000 );
        $('#orders-table').on( 'draw.dt', function () {
            setTimeout( function () {
                $('#orders-table').DataTable().ajax.reload(null, false);
            }, 5000 );
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