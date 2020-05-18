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
        @include('statistic.base.form_dates', ['route' => $DTRender->getRouteIndex()])
    </div>
</div>

<div class="box">
    <div class="box-header">{{$DTRender->getLabelHeader()}}</div>

    <div class="box-body">
        {!! $DTRender->renderTable() !!}
    </div>
</div>
