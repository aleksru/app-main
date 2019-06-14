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
    <div class="col-md-12">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="alert">
            @include('front.widgets.delivery_periods_widget')
        </div>
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
            <products-table :initial_data="{{ json_encode($order->realizations, true) }}"
                            :initial_order="{{ json_encode($order->id, true) }}"
                            :initial_price_delivery="{{ $order->deliveryType->price ?? 0 }}"
                            :suppliers="{{  json_encode(\App\Models\Supplier::select('id', 'name')->get()) }}"
                            :show_search="true">
            </products-table>
        </div>
    </div>

@endsection

