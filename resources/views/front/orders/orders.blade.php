@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Заказы
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
              <li>Товары: Количество-Артикул-Название-ЦенаЗаШт</li>
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
                    'id' => [
                        'name' => 'ID',
                        'width' => '1%',
                        'searchable' => true,
                    ],
                    'status_text' => [
                        'name' => 'Статус',
                        'width' => '2%',
                        'searchable' => false,
                    ],
                    'store_text' => [
                        'name' => 'Магазин',
                        'width' => '3%',
                        'searchable' => true,
                        'orderable' => 'false'
                    ],
                    'name_customer' => [
                        'name' => 'Покупатель',
                        'width' => '3%',
                        'searchable' => true,
                        'orderable' => 'false'
                    ],
                    'phone' => [
                        'name' => 'Телефон',
                        'width' => '3%',
                        'searchable' => true,
                    ],
                    'additional_phones' => [
                        'name' => 'Доп телефоны',
                        'width' => '3%',
                        'searchable' => true,
                        'orderable' => 'false'
                    ],
                    'communication_time' => [
                        'name' => 'Время перезвона',
                        'width' => '3%',
                        'searchable' => false,
                    ],
                    'courier' => [
                        'name' => 'Курьер',
                        'width' => '3%',
                        'searchable' => false,
                        'orderable' => 'false'

                    ],
                    'products' => [
                        'name' => 'Товары',
                        'width' => '20%',
                        'searchable' => false,
                        'orderable' => 'false'
                    ],
                    'comment' => [
                        'name' => 'Комментарий',
                        'width' => '5%',
                        'searchable' => false,
                        'orderable' => 'false'
                    ],
                    'created_at' => [
                        'name' => 'Дата создания',
                        'width' => '5%',
                        'orderable' => 'false'
                    ],
                    'actions' => [
                        'name' => 'Действия',
                        'width' => '2%',
                        'orderable' => 'false'
                    ],

                ],
            ])
        </div>
@endsection

@push('scripts')
<script>
    @if (session()->has('success'))
        toast.success('{{ session()->get('success') }}')
    @endif
    @if (session()->has('error'))
        toast.error('{{ session()->get('error') }}')
    @endif

    $(function () {
        setInterval( function () {
            $('#orders-table').DataTable().ajax.reload(null, false);
        }, 5000 );
    });

    $('#orders-table').on( 'draw.dt', function () {
        $('.row-link').click(function(){
            window.open($(this).find('a').first().attr('href'), '_blank');
        });
    } );
</script>
@endpush
