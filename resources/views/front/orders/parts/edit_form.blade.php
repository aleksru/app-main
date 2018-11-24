<form id="order-form" role="form" method="post" class="form-horizontal" action="{{ isset($order) ? route('orders.update', $order->id) :  route('orders.store') }}">
    {{ csrf_field() }}

    @if (isset($order))
        {{ method_field('PUT') }}
    @endif

    <div class="row">
        <div class="col-sm-4">
            <label for="name" class="control-label">№ ЗАКАЗА</label>
            <input type="text" class="form-control"  value="{{ $order->id ?? '' }}" disabled>
        </div>

        <div class="col-sm-4">
            <label for="name" class="control-label">Дата заказа</label>
            <input type="text" class="form-control"  value="{{ $order->created_at ?? '' }}" disabled>
        </div>

        <div class="col-sm-4">
            <label for="name" class="control-label">Источник</label>
            <input type="text" class="form-control"  value="{{ $order->store ?? '' }}" name="store">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <label for="name" class="control-label">Статус</label>
            <select class="js-example-basic-single form-control" name="status_id">
                    <option value="{{ $order->status->id ?? null }}" selected>{{ $order->status->status ?? 'Не выбран' }}</option>
                    <option value="{{ null }}">  </option>
            </select>
        </div>
        <div class="col-sm-4">
            <label for="name" class="control-label">Оператор</label>
            <select class="js-example-operator-single form-control" name="operator_id">
                <option value="{{ $order->operator->id ?? null }}" selected>{{ $order->operator->name ?? 'Не выбран' }}</option>
                <option value="{{ null }}">  </option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <label for="name" class="control-label">Курьер</label>
            <select class="js-example-couriers-single form-control" name="courier_id">
                <option value="{{ $order->courier->id ?? null }}" selected>{{ $order->courier->name ?? 'Не выбран' }}</option>
                <option value="{{ null }}">  </option>
            </select>
        </div>

        <div class="col-sm-4">
            <label for="name" class="control-label">Дата доставки</label>
            <input type="date" class="form-control"  value="{{ $order->date_delivery ?? null  }}" name="date_delivery">
        </div>

        <div class="col-sm-4">
            <label for="name" class="control-label">Время доставки</label>
            <select class="js-example-period-single form-control" name="delivery_period_id">
                <option value="{{ $order->deliveryPeriod->id ?? null }}" selected>{{ $order->deliveryPeriod->period ?? 'Не выбран' }}</option>
                <option value="{{ null }}">  </option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8">
            <label for="name" class="control-label">Комментарий</label>
            <textarea class="form-control" rows="4" name="comment" placeholder="Комментарий ..."> {{ $order->comment ?? '' }}</textarea>
        </div>
    </div>

    {{--<div class="row">--}}
        {{--<div class="col-sm-12">--}}
            {{--<label for="name" class="control-label"></label>--}}
                {{--<div class="box box-default collapsed-box">--}}
                    {{--<div class="box-header with-border">--}}
                        {{--<h3 class="box-title">Товары в заказе</h3>--}}

                        {{--<div class="box-tools pull-right">--}}
                            {{--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>--}}
                            {{--</button>--}}
                        {{--</div>--}}
                        {{--<!-- /.box-tools -->--}}
                    {{--</div>--}}
                    {{--<!-- /.box-header -->--}}
                    {{--<div class="box-body" style="display: none;">--}}
                        {{--@if (isset($order) && $order->products)--}}
                            {{--<div class="col-xs-12 table-responsive">--}}
                            {{--<table class="table table-striped">--}}
                                {{--<thead>--}}
                                    {{--<tr>--}}
                                        {{--<th>Кол-во</th>--}}
                                        {{--<th>Товар</th>--}}
                                        {{--<th>Цена</th>--}}
                                    {{--</tr>--}}
                                {{--</thead>--}}
                                {{--<tbody>--}}
                                    {{--@foreach ($order->products as $product)--}}
                                        {{--<tr>--}}
                                            {{--<td>{{ $product["quantity"] ?? '' }}</td>--}}
                                            {{--<td>{{ $product["name"] ?? '' }}</td>--}}
                                            {{--<td>{{ $product["price"] ?? '' }} p.</td>--}}
                                        {{--</tr>--}}
                                    {{--@endforeach--}}
                                {{--</tbody>--}}
                            {{--</table>--}}
                        {{--</div>--}}
                        {{--@else--}}
                            {{--Отсутствуют--}}
                        {{--@endif--}}
                    {{--</div>--}}
                    {{--<!-- /.box-body -->--}}
                {{--</div>--}}
                {{--<!-- /.box -->--}}

        {{--</div>--}}
    {{--</div>--}}


    <input type="hidden" class="form-control"  value="{{ Auth::user()->id }}" name="user_id">

    <button form="order-form" type="submit" class="btn btn-primary pull-right">
        <i class="fa fa-save"></i> Сохранить
    </button>
</form>


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
                    allowClear: true,
                    placeholder: "Выберите статус...",
                });
            });

        let couriers = {!!   json_encode(\App\Models\Courier::select('id', 'name as text')->get()->toArray()) !!}
        $(function() {
            $('.js-example-couriers-single').select2({
                data: couriers,
                allowClear: true,
                placeholder: "Выберите курьера...",
            });
        });

        let periods = {!!   json_encode(\App\Models\DeliveryPeriod::select('id', 'period as text')->get()->toArray()) !!}
        $(function() {
            $('.js-example-period-single').select2({
                data: periods,
                allowClear: true,
                placeholder: "Выберите время...",
            });
        });

        let operators = {!!   json_encode(\App\Models\Operator::select('id', 'name as text')->get()->toArray()) !!}
        $(function() {
            $('.js-example-operator-single').select2({
                data: operators,
                allowClear: true,
                placeholder: "Выберите оператора...",
            });
        });
    </script>
@endpush