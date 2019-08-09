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
        <div class="block_info">
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
            <order-form inline-template>
                <products-table ref="ProductsTable"
                                @submit-form="submit()"
                                :initial_data="{{ json_encode($order->realizations, true) }}"
                                :initial_order="{{ json_encode($order->id, true) }}"
                                :initial_price_delivery="{{ $order->deliveryType->price ?? 0 }}"
                                :suppliers="{{  json_encode(\App\Models\Supplier::select('id', 'name')->get()) }}"
                                :show_search="true"
                                :initial_product_types="{{  json_encode(array_values(\App\Enums\ProductType::getConstantsForDescription())) }}"
                >
                </products-table>
            </order-form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="order_info_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="order_info_modal_label">Обновление заказа</h4>
                </div>
                <div class="progress progress-sm active">
                    <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        <span class="sr-only">20% Complete</span>
                    </div>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <button type="submit" id="send_all_forms" class="btn btn-primary btn-lg pull-right">
            <i class="fa fa-save"></i> Сохранить все
        </button>
    </div>

@endsection

