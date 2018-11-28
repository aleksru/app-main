@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Заказы
            <small>Страница заказа</small>
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
            <div class="box box-solid">
                <div class="box-body">
                    <h4> Заказ </h4>
                    @include('front.orders.parts.edit_form')
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6">
            <div class="box box-solid">
                <div class="box-body">
                    <h4> Клиент
                        {{--<a href="{{ route('clients.show', $order->client->id) }}">--}}
                            {{--<i class="fa fa-address-card" aria-hidden="true"></i>--}}
                        {{--</a>--}}
                    </h4>
                    @include('front.orders.parts.widget_user', ['client' => $order->client ?? null])
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            {{--<div class="box box-solid">--}}

                    {{--@component('layouts.forms.vue-select', [--}}
                           {{--'name' => 'departure_city_id',--}}
                           {{--'label' => 'Город отправления',--}}
                           {{--'multiple' => false,--}}
                           {{--'value' =>  [] ?? null,--}}
                           {{--'valueColumn' => 'id',--}}
                           {{--'labelColumn' => 'name',--}}
                           {{--'empty' => true,--}}
                           {{--//'search' => route('api.cities.search'),--}}
                           {{--'options' => \App\Models\Operator::all(),--}}
                           {{--'placeholder' => 'Выберите город',--}}
                       {{--])--}}
                    {{--@endcomponent--}}
                    @include('front.orders.parts.products-table', ['order' => $order])
                </div>
            {{--</div>--}}
        </div>
    </div>



@endsection

