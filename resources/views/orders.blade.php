@extends('layouts.adminlte.app')

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
              <li>Взять в работу - <i class="fa fa-user-plus btn btn-sm btn-warning"></i> </li>
              <li>Завершить заказ - <i class="fa fa-check-square-o btn btn-sm btn-success"></i>  </li>
          </ul>
        </div>
        <div class="col-md-12">
            @include('datatable.datatable',[
                'id' => 'orders-table',
                'route' => route('orders.datatable'),
                'columns' => [
                    'id' => [
                        'name' => 'ID',
                        'width' => '1%',
                        'searchable' => true,
                    ],
                    'status' => [
                        'name' => 'Статус',
                        'width' => '2%',
                        'searchable' => true,
                    ],
                    'store' => [
                        'name' => 'Магазин',
                        'width' => '3%',
                        'searchable' => true,
                    ],
                    'name_customer' => [
                        'name' => 'Покупатель',
                        'width' => '3%',
                        'searchable' => true,
                    ],
                    'phone' => [
                        'name' => 'Телефон',
                        'width' => '3%',
                        'searchable' => true,
                    ],
                    'products' => [
                        'name' => 'Товары',
                        'width' => '20%',
                        'searchable' => true,
                    ],
                    'comment' => [
                        'name' => 'Комментарий',
                        'width' => '5%',
                        'searchable' => true,
                    ],
                    'total' => [
                        'name' => 'Общая стоимость',
                        'width' => '5%',
                        'orderable' => 'false'
                    ],
                    'created_at' => [
                        'name' => 'Дата создания',
                        'width' => '5%',
                        'orderable' => 'false'
                    ],
                    'actions' => [
                        'name' => 'Действия',
                        'width' => '5%',
                        'orderable' => 'false'
                    ],

                ],
            ])
        </div>
@endsection

@section('header_scripts')
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
@endsection
