@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Заказы
            <small>Страница заказа</small>
            <small> <a href="{{ route('logs.order', $order->id) }}">Лог заказа</a> </small>
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

    <div class="row">
        <div class="col-sm-12 col-md-6">
            @include('front.orders.parts.edit_form')
        </div>

        <div class="col-sm-12 col-md-6">
            @include('front.orders.parts.widget_user', ['client' => $order->client ?? null])
            @include('front.orders.parts.comment_logist', ['order' => $order ?? ''])
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            @include('front.orders.parts.products-table', ['order' => $order])
        </div>
    </div>

@endsection

