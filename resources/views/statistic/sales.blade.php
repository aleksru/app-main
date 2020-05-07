@extends('layouts.adminlte.app')

@section('content')
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Выберите даты</h3>
        </div>
        <div class="box-body">
            <form id="report" method="get" action="{{ route('statistic.sales') }}">
                <div class="row">
                    <div class="col-md-2">
                        <label>Дата начала</label>
                        <input type="date" class="form-control" name="dateFrom" id="dateFrom">
                    </div>
                    <div class="col-md-2">
                        <label>Дата окончания</label>
                        <input type="date" name="dateTo" class="form-control" id="dateTo">
                    </div>
                    <div class="col-md-2">
                        <label></label>
                        <button type="submit" class="btn btn-primary form-control" id="btn-result">
                            <p>Вывести</p>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="box">
        <div class="box-header">Продажи\Отказы</div>

        <div class="box-body">
            @include('charts.chart', compact('chart'))
        </div>
    </div>

    <div class="col-md-6">
        <div class="box">
            <div class="box-header">Продажи по категориям</div>

            <div class="box-body">
                @include('charts.chart', ['chart' => $pieCategories])
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="box">
            <div class="box-header">Топ товаров</div>

            <div class="box-body">
                @include('charts.chart', ['chart' => $pieTopProducts])
            </div>
        </div>
    </div>
@endsection

