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

    <div class="box box-warning">

        <div class="box-header">
        </div>

        <div class="box-body">

            @if ($errors->any())
                <div class="callout callout-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            @endif

            <form id="user-form" role="form" method="post" class="form-horizontal" action="">
                {{ csrf_field() }}

                {{--@if (isset($operator))--}}
                    {{--{{ method_field('PUT') }}--}}
                {{--@endif--}}
                <div class="row">
                    <div class="col-sm-2">
                        <label for="name" class="control-label">№ ЗАКАЗА</label>
                        <input type="text" class="form-control"  value="{{ $order->id }}" disabled>
                    </div>
                    <div class="col-sm-2">
                        <label for="name" class="control-label">Дата заказа</label>
                        <input type="text" class="form-control"  value="{{ $order->created_at }}" disabled>
                    </div>
                    <div class="col-sm-2">
                        <label for="name" class="control-label">Статус</label>
                        <select class="js-example-basic-single form-control" name="status_id">
                                <option value="{{ $order->status->id ?? null }}" selected>{{ $order->status->status ?? 'Не выбран' }}</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label for="name" class="control-label">Курьер</label>
                        <select class="js-example-couriers-single form-control" name="courier_id">
                                <option value="{{ $order->courier->id ?? null }}" selected>{{ $order->courier->name ?? 'Не выбран' }}</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label for="name" class="control-label">Комментарий</label>
                        <textarea class="form-control" rows="3" name="comment" placeholder="Комментарий ..."> {{ $order->comment ?? '' }}</textarea>
                    </div>
                    <div class="col-sm-2">
                        <label for="name" class="control-label">Время доставки</label>
                        <select class="js-example-period-single form-control" name="delivery_period_id">
                            <option value="{{ $order->deliveryPeriod->id ?? null }}" selected>{{ $order->deliveryPeriod->period ?? 'Не выбран' }}</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label for="name" class="control-label">ФИО</label>
                        <input type="text" class="form-control" name="name" placeholder="ФИО" value="">
                    </div>
                </div>
            </form>
        </div>
        <div class="box-footer">
            <button form="user-form" type="submit" class="btn btn-primary pull-right">
                <i class="fa fa-save"></i> Сохранить
            </button>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    @if (session()->has('success'))
        toast.success('{{ session()->get('success') }}')
    @endif
    @if (session()->has('error'))
        toast.error('{{ session()->get('error') }}')
    @endif

    let statuses = {!!   json_encode(\App\Models\OrderStatus::select('id', 'status as text', 'color')->get()->toArray()) !!}
        $(function() {
            $('.js-example-basic-single').select2({
                data: statuses,
            });
        });

    let couriers = {!!   json_encode(\App\Models\Courier::select('id', 'name as text')->get()->toArray()) !!}
    $(function() {
        $('.js-example-couriers-single').select2({
            data: couriers,
        });
    });

    let periods = {!!   json_encode(\App\Models\DeliveryPeriod::select('id', 'period as text')->get()->toArray()) !!}
    $(function() {
        $('.js-example-period-single').select2({
            data: periods,
        });
    });
</script>
@endpush