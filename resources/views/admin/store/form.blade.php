@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Админ панель
            <small>Магазины</small>
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
                {{ isset($store) ? 'Редактирование магазина' : 'Создание магазина' }}
            </h3>
        </div>

        <div class="box-body">

            @if ($errors->any())
                <div class="callout callout-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            @endif

            <form id="user-form" role="form" method="post" class="form-horizontal" action="{{
                isset($store) ? route('admin.stores.update', $store->id) : route('admin.stores.store')
            }}">
                {{ csrf_field() }}

                @if (isset($store))
                    {{ method_field('PUT') }}
                @endif

                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Название</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" placeholder="Название" value="{{ old('name', $store->name ?? '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Телефон</label>

                    <div class="col-sm-10">
                        <input type="text"
                               class="form-control"
                               name="phone"
                               placeholder="Телефон"
                               value="{{ old('phone', ($store->phone ?? false) ? $store->phone : '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">HTTP</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="url" placeholder="Url" value="{{ old('url', $store->url ?? 'http://') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="address" class="col-sm-2 control-label">Адрес</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="address" placeholder="Адрес" value="{{ old('address', $store->address ?? '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-sm-2 control-label">Описание</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="description" placeholder="Описание" value="{{ old('description', $store->description ?? '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="price_type_id" class="col-sm-2 control-label">Прайс-лист</label>

                    <div class="col-sm-6">
                        <select class="js-example-pricelists-single form-control" name="price_type_id">
                            @if(old('price_type_id'))
                                <option value="{{ old('price_type_id') }}" selected>
                                    {{ \App\PriceType::find(old('price_type_id'))->name ?? '' }}
                                </option>
                            @else
                                <option value="{{ old('price_type_id', $store->priceType->id ?? null) }}" selected>
                                    {{ old('price_type_id', $store->priceType->name ?? 'Не выбран') }}
                                </option>
                            @endif
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="price_type_id" class="col-sm-2 control-label">Статус заказа по-умолчанию</label>

                    <div class="col-sm-6">
                        <select class="js-example-dosi-single form-control" name="default_order_status_id">
                            @if(old('default_order_status_id'))
                                <option value="{{ old('default_order_status_id') }}" selected>
                                    {{ \App\Models\OrderStatus::find(old('default_order_status_id'))->status ?? '' }}
                                </option>
                            @else
                                <option value="{{ old('default_order_status_id', $store->defaultOrderStatus->id ?? null) }}" selected>
                                    {{ old('default_order_status_id', $store->defaultOrderStatus->status ?? 'Не выбран') }}
                                </option>
                            @endif
                            <option value="">
                                Не выбран
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="price_type_id" class="col-sm-2 control-label"></label>
                    <div class="col-sm-6">
                        <div class="pretty p-default p-curve p-thick">
                            <input type="hidden" value="0" name="is_disable_api_price" />
                            <input class="form-control" type="checkbox" value="1" name="is_disable_api_price"
                                   @if(isset($store) && $store->is_disable_api_price)checked @endif
                            />
                            <div class="state p-danger-o">
                                <label>Не проставлять цены из API</label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            @include('admin.remote-stores.parts.button_update_price', ['store' => $store ?? null])
            @include('admin.remote-stores.parts.btn_check_state', ['store' => $store ?? null])

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
        let pricelists = {!!   json_encode(\App\PriceType::select('id', 'name as text')->get()->toArray()) !!}
        $(function() {
            $('.js-example-pricelists-single').select2({
                data: pricelists,
            });
        });
        let orderStatuses = {!!   json_encode(\App\Models\OrderStatus::select('id', 'status as text')->get()->toArray()) !!}
        $(function() {
            $('.js-example-dosi-single').select2({
                data: orderStatuses,
            });
        });
    </script>
@endpush
