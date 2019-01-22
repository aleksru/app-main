@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Прайс-лист
            <small>{{ $priceType->name }}</small>
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
    <div class="box-header">
    </div>
    <div class="col-md-12">
        @include('datatable.datatable',[
            'id' => 'orders-table',
            'route' => route('price-lists.show.datatable', ['price_list' => $priceType->id]),
            'columns' => [
                'article' => [
                    'name' => 'Артикул',
                    'width' => '5%',
                    'searchable' => true,
                ],
                'product_name' => [
                    'name' => 'Товар',
                    'width' => '20%',
                    'searchable' => true,
                ],
                'price' => [
                    'name' => 'Цена',
                    'width' => '5%',
                    'searchable' => false,
                ],
                'updated_at' => [
                    'name' => 'Обновлен',
                    'width' => '5%',
                    'searchable' => false,
                    'orderable' => 'false'
                ],
            ],
        ])
    </div>
@endsection