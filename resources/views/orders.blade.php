@extends('layouts.app')

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
