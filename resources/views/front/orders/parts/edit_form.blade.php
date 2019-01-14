<div class="box box-solid">
    <div class="box-body">
        <h4> Заказ
            <a href="{{ route('docs.market-check',$order->id) }}">
                <i class="fa fa-file-excel-o btn btn-info pull-right" aria-hidden="true">   Скачать счет</i>
            </a>
        </h4>

        <form id="order-form" role="form" method="post" class="form-horizontal" action="{{ isset($order) ? route('orders.update', $order->id) :  route('orders.store') }}">
            {{ csrf_field() }}

            @if (isset($order))
                {{ method_field('PUT') }}
            @endif

            <div class="row">
                <div class="col-sm-2">
                    <label for="name" class="control-label">№</label>
                    <input type="text" class="form-control"  value="{{ $order->id ?? '' }}" disabled>
                </div>

                <div class="col-sm-5">
                    <label for="name" class="control-label">Дата заказа</label>
                    <input type="text" class="form-control"  value="{{ $order->created_at ?? '' }}" disabled>
                </div>

                <div class="col-sm-5">
                    {{--<label for="name" class="control-label">Источник*</label>--}}
                    {{--<input type="text" class="form-control"  value="{{ old('store', $order->store ?? '' ) }}" name="store">--}}
                    <label for="name" class="control-label">Источник</label>
                    <select class="js-example-stores-single form-control" name="store_id">
                        <option value="{{ $order->store->id ?? null }}" selected>{{ $order->store->name ?? 'Не выбран' }}</option>
                        <option value="{{ null }}">  </option>
                    </select>
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

                @if($order->status && stripos($order->status->status, 'отказ') !== false)
                    <div class="col-sm-4">
                        <label for="name" class="control-label">Причина отказа</label>
                        <select class="js-example-reasons-single form-control" name="denial_reason_id">
                            <option value="{{ $order->denialReason->id ?? null }}" selected>{{ $order->denialReason->reason ?? 'Не выбран' }}</option>
                            <option value="{{ null }}">  </option>
                        </select>
                    </div>
                @endif
            </div>

            <div class="row">

                <div class="col-sm-5">
                    <label for="name" class="control-label">Оператор</label>
                    <select class="js-example-operator-single form-control" name="operator_id">
                        <option value="{{ $order->operator->id ?? null }}" selected>{{ $order->operator->name ?? 'Не выбран' }}</option>
                        <option value="{{ null }}">  </option>
                    </select>
                </div>
                <div class="col-sm-5">
                    <label for="name" class="control-label">Перезвон</label>
                    <input type="datetime-local" class="form-control"  value="{{
                        old('communication_time', $order->communication_time ? $order->communication_time->format('Y-m-d\TH:i') : null )  }}" name="communication_time">
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
                    <input type="date" class="form-control"  value="{{  old('date_delivery', $order->date_delivery ?? null )  }}" name="date_delivery">
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
                <div class="col-sm-4">
                    <label for="metro" class="control-label">Метро</label>
                    <select class="js-example-metros-single form-control" name="metro_id">
                        <option value="{{ $order->metro->id ?? null }}" selected>{{ $order->metro->name ?? 'Не выбрана' }}</option>
                        <option value="{{ null }}">  </option>
                    </select>
                </div>

                <div class="col-sm-8">
                    <label for="address" class="control-label">Адрес доставки*</label>
                    <input type="text" class="form-control"  value="{{ old('address', $order->address ?? '')  }}" name="address">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8">
                    <label for="name" class="control-label">Комментарий</label>
                    <textarea class="form-control" rows="4" name="comment" placeholder="Комментарий ..."> {{ old('comment', $order->comment ?? '') }}</textarea>
                </div>
                <br>
                <div class="col-sm-4">
                    <label>
                        <input type="checkbox" name="flag_denial_acc"
                              @if($order->flag_denial_acc) checked @endif>Отказ от акс-ов
                    </label>
                    <button form="order-form" type="submit" class="btn btn-primary pull-right ">
                        <i class="fa fa-save"></i> Сохранить
                    </button>
                </div>

                <input type="hidden" class="form-control"  value="{{ Auth::user()->id }}" name="user_id">
            </div>
        </form>
    </div>
</div>

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

        let metros = {!!   json_encode(\App\Models\Metro::select('id', 'name as text')->get()->toArray()) !!}
        $(function() {
            $('.js-example-metros-single').select2({
                data: metros,
                allowClear: true,
                placeholder: "Выберите станцию метро...",
            });
        });

        let stores = {!!   json_encode(\App\Store::select('id', 'name as text')->get()->toArray()) !!}
        $(function() {
            $('.js-example-stores-single').select2({
                data: stores,
                allowClear: true,
                placeholder: "Выберите магазин...",
            });
        });

        let denialReasons = {!!   json_encode(\App\Models\DenialReason::select('id', 'reason as text')->get()->toArray()) !!}
        $(function() {
            $('.js-example-reasons-single').select2({
                data: denialReasons,
                allowClear: true,
                placeholder: "Причина отказа...",
            });
        });
    </script>
@endpush