@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Профиль клиента
            {{--<small>Optional description</small>--}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>
    </section>
@endsection
@section('content')
    <div class="col-md-9">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#activity" data-toggle="tab">Основное</a></li>
                <li><a href="#timeline" data-toggle="tab">Заказы</a></li>
                <li><a href="#settings" data-toggle="tab">Звонки</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane" id="activity">
                    <ul class="list-group">
                        <li class="list-group-item">Имя: {{ $client->name ?? '' }}</li>
                        <li class="list-group-item">Телефон: {{ $client->phone ?? '' }}</li>
                    </ul>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="timeline">
                    <ul class="list-group">
                        @foreach($client->orders as $order)
                            <li class="list-group-item">ID: {{ $order->id }} Дата: {{ $order->created_at }}</li>
                        @endforeach
                    </ul>
                </div>
                <!-- /.tab-pane -->

                <div class="tab-pane" id="settings">
                    <ul class="list-group">
                        @foreach($client->calls as $call)
                            <li class="list-group-item">Дата: {{ $call->created_at }} Комментарий: {{ $call->description ?? '' }}</li>
                        @endforeach
                    </ul>
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- /.nav-tabs-custom -->
    </div>
@endsection
