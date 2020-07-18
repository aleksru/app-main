<?php
/**
 * @var App\Services\Statistic\Abstractions\BaseReportTableRender $DTRender
 */
?>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Выберите даты</h3>
    </div>
    <div class="box-body">
        @component('statistic.base.form_dates', ['route' => $DTRender->getRouteIndex()])
            <div class="col-md-2">
                <label>Магазин</label>
                <select name="store_id" class="form-control">
                    <option value=""></option>
                    @foreach($stores = \App\Store::active()->get() as $store)
                        <option value="{{$store->id}}" @if(request()->get('store_id') == $store->id) selected @endif>{{$store->name}}</option>
                    @endforeach
                </select>
            </div>
        @endcomponent
    </div>
</div>

<div class="box">
    <div class="box-header">{{$DTRender->getLabelHeader()}}</div>

    <div class="box-body">
        {!! $DTRender->renderTable() !!}
    </div>
</div>
