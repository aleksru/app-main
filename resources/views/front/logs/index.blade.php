@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Logs
            <small>Лог заказа</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>
    </section>
@endsection
@section('content')
    <div class="box box-warning">

        <div class="box-header">
            <h3 class="box-title">

            </h3>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="box-header with-border">
                    <h3 class="box-title">Список</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered" id="table-prices">
                        <tbody>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Пользователь</th>
                            <th>Раздел</th>
                            <th>Тип</th>
                            <th>Действия</th>
                            <th>Дата</th>
                        </tr>
                        @foreach ($logs as $log)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $log->user ? $log->user->name : 'системный' }}</td>
                                <td>{{ \App\Logging\NameEntity::getNameEntity($log->logtable_type) }}</td>
                                <td>{{ $log->type ?? 'не определен' }}</td>
                                <td>{{ $log->actions }}</td>
                                <td>{{ $log->created_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection