@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
           Админ панель
            <small>Пользователи</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>
    </section>
@endsection
@section('content')
    <div class="col-md-8">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="box box-warning">

        <div class="box-header">
            <a href="{{route('admin.users.create')}}"><button class="btn btn-sm btn-primary pull-right">
                    <i class="fa fa-plus"></i> Создать
                </button></a>
        </div>

        <div class="box-body">
            @include('datatable.datatable',[
                'id' => 'orders-table',
                'route' => route('admin.users.datatable'),
                'columns' => [
                    'id' => [
                        'name' => 'ID',
                        'width' => '1%',
                        'searchable' => true,
                    ],
                    'name' => [
                        'name' => 'Login',
                        'width' => '10%',
                        'searchable' => true,
                    ],
                    'actions' => [
                        'name' => 'Действия',
                        'width' => '5%',
                        'orderable' => 'false'
                    ],

                ],
            ])
        </div>
    </div>
@endsection

