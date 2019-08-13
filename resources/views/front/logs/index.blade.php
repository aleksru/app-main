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

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Лог заказа</a></li>
                <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">СМС</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
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
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
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
                                        <th>Ключ</th>
                                        <th>Текст</th>
                                        <th>Время</th>
                                    </tr>
                                    @foreach ($orderSms as $sms)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $sms->command_id }}</td>
                                            <td>{{ $sms->text }}</td>
                                            <td>{{ $sms->created_at->format('d.m.y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.tab-content -->
        </div>
    </div>
@endsection