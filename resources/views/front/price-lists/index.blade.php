@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Прайс-листы
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
    </div>
    <div class="box-header">
    </div>
    <div class="col-md-12">
        @include('datatable.datatable',[
            'id' => 'orders-table',
            'route' => route('price-lists.datatable'),
            'columns' => [
                'id' => [
                    'name' => 'ID',
                    'width' => '5%',
                    'searchable' => true,
                 ],
                'name' => [
                    'name' => 'Название',
                    'width' => '20%',
                    'searchable' => true,
                ],
                'actions' => [
                    'name' => 'Действия',
                    'width' => '10%',
                ],
            ],
        ])
    </div>
@endsection
