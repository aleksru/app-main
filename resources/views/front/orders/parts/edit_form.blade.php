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
                <div class="col-sm-4">
                    <label for="name" class="control-label">№</label>
                    <input type="text" class="form-control"  value="{{ $order->id ?? '' }}" disabled>
                </div>

                <div class="col-sm-4">
                    <label for="name" class="control-label">Дата заказа</label>
                    <input type="text" class="form-control"  value="{{ $order->created_at ?? '' }}" disabled>
                </div>

                <div class="col-sm-4">
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
                @include('front.orders.parts.statuses', ['order' => $order, 'operatorStatuses' => $operatorStatuses])
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <label for="name" class="control-label">Статус склад</label>
                    <select class="form-control" name="stock_status_id">
                        <option value="" @if(empty($order->stockStatus->id)) selected @endif>Не выбран</option>
                        @foreach($stockStatuses as $stockStatus)
                            <option value="{{ $stockStatus->id }}"
                                @if(($order->stockStatus->id ?? null) === $stockStatus->id) selected @endif>{{ $stockStatus->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-sm-4">
                    <label for="name" class="control-label">Статус логистика</label>
                    <select class="form-control" name="logistic_status_id">
                        <option value="" @if(empty($order->logisticStatus->id)) selected @endif>Не выбран</option>
                        @foreach($logisticStatuses as $logisticStatus)
                            <option value="{{ $logisticStatus->id }}"
                                @if(($order->logisticStatus->id ?? null) === $logisticStatus->id) selected @endif>{{ $logisticStatus->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-sm-4">
                    <label for="name" class="control-label">Тип доставки</label>
                    <select class="js-example-delivery-type-single form-control" name="delivery_type_id">
                        <option value="{{ $order->deliveryType->id ?? null }}" selected>{{ $order->deliveryType->type ?? 'Не выбран' }}</option>
                        <option value="{{ null }}">  </option>
                    </select>
                </div>
            </div>

            <div class="row">

                <div class="col-sm-5">
                    <label for="name" class="control-label">Оператор</label>
                    <select class="js-example-operator-single form-control" name="operator_id">
                        <option value="{{ $operator->id ?? '' }}" selected>{{ $operator->name ?? 'Не выбран' }}</option>
                        <option value="{{ null }}">  </option>
                    </select>
                </div>
                <div class="col-sm-5">
                    <label for="name" class="control-label">Перезвон</label>
                    <input type='text' name="communication_time" class="form-control" autocomplete="off"
                           value="{{ $order->communication_time ?  $order->communication_time->format('d.m.Y H:i:s') : '' }}"/>
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
                    <input type="date" class="form-control"
                           value="{{  old('date_delivery',
                           $order->date_delivery ? $order->date_delivery->toDateString() : null )  }}"
                           name="date_delivery"
                    >
                </div>

                <div class="col-sm-4">
                    <label for="name" class="control-label">Время доставки</label>
                    <select class="js-example-period-single form-control" name="delivery_period_id">
                        <option value="{{ $order->deliveryPeriod->id ?? null }}" selected>{{ $order->deliveryPeriod->period_full ?? 'Не выбран' }}</option>
                        <option value="{{ null }}">  </option>
                    </select>
                </div>

            </div>

            <div class="row">

                <div class="col-sm-3">
                    <label for="address" class="control-label">Город</label>
                    <input type="text" class="form-control"  value="{{ old('address_city', $order->address_city ?? '')  }}" name="address_city">
                </div>

                <select-input :value-city="{{json_encode($order->city->id ?? null)}}"
                              :value-metro="{{json_encode($order->metro->id ?? null)}}"
                              :options="{{json_encode(\App\Models\City::select("id", "name")->get(), true)}}">
                </select-input>

                <div class="col-sm-3">
                    <label for="address" class="control-label">Улица</label>
                    <input type="text" class="form-control"  value="{{ old('address_street', $order->address_street ?? '')  }}" name="address_street">
                </div>
            </div>

            <div class="row">

                <div class="col-sm-4">
                    <label for="address" class="control-label">Дом</label>
                    <input type="text" class="form-control"  value="{{ old('address_home', $order->address_home ?? '')  }}" name="address_home">
                </div>

                <div class="col-sm-4">
                    <label for="address" class="control-label">Квартира</label>
                    <input type="text" class="form-control"  value="{{ old('address_apartment', $order->address_apartment ?? '')  }}" name="address_apartment">
                </div>

                <div class="col-sm-4">
                    <label for="address" class="control-label">Адрес прочее</label>
                    <input type="text" class="form-control"  value="{{ old('address_other', $order->address_other ?? '')  }}" name="address_other">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8">
                    <label for="name" class="control-label">Комментарий</label>
                    <textarea class="form-control" rows="4" name="comment" placeholder="Комментарий ..."> {{ old('comment', $order->comment ?? '') }}</textarea>
                </div>
                <br>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Отказ от аксессуаров</label>
                        <select class="form-control" name="flag_denial_acc">
                            <option value="0">Нет</option>
                            <option value="1"  @if($order->flag_denial_acc) selected @endif>Да</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>SMS</label>
                        <select class="form-control" name="flag_send_sms">
                            <option value="0">Не отправлено</option>
                            <option value="1"  @if($order->flag_send_sms) selected @endif>Отправлено</option>
                        </select>
                    </div>
                    {{--<button form="order-form" type="submit" class="btn btn-primary pull-right ">--}}
                        {{--<i class="fa fa-save"></i> Сохранить--}}
                    {{--</button>--}}
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
        @if (session()->has('warning'))
            toast.warning('{{ session()->get('warning') }}')
        @endif
        $(function() {
            $('input[name="communication_time"]').datepicker({
                timepicker: true,
                minDate: new Date(),
                clearButton: true,
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

        let periods = {!!   json_encode(\App\Models\DeliveryPeriod::selectRaw('id, CONCAT(IFNULL(timeFrom, ""), "-", IFNULL(timeTo, ""), " ", IFNULL(period, "")) as text')->get()->toArray()) !!}
        $(function() {
            $('.js-example-period-single').select2({
                data: periods,
                allowClear: true,
                placeholder: "Выберите время...",
            });
        });

        let operators = {!! json_encode($listOperators) !!}
        $(function() {
            $('.js-example-operator-single').select2({
                data: operators,
                allowClear: false,
                placeholder: "Выберите оператора...",
            });
        });

        let stores = {!!   json_encode(\App\Store::active()->select('id', 'name as text')->get()->toArray()) !!}
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

        let deliveryType = {!!   json_encode(\App\Models\DeliveryType::select('id', 'type as text')->get()->toArray()) !!}
        $(function() {
            $('.js-example-delivery-type-single').select2({
                data: deliveryType,
                allowClear: true,
                placeholder: "Тип доставки",
            });
        });
    </script>
@endpush
