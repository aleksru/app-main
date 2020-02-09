
@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Отчеты
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>
    </section>
@endsection
@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="col-md-12">
        <div class="box box-default">
            <div class="box-header">
                <h3>Операторы</h3>
            </div>
            <div class="box-header with-border">

            </div>
            <div class="box-body">
                <div class="col-md-4">
                    <form id="report" method="get" action="{{ route('reports.operators.orders') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Дата начала</label>
                                <input type="date" class="form-control" name="dateFrom" id="dateFrom" required>
                            </div>
                            <div class="col-md-4">
                                <label>Дата окончания</label>
                                <input type="date" name="dateTo" class="form-control" id="dateTo">
                            </div>
                            <div class="col-md-4">
                                <label></label>
                                <button form="report" type="submit" class="btn btn-primary form-control" id="btn-result">
                                    <p>Вывести</p>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-2">
                    <form id="report_to_day" method="get" action="{{ route('reports.operators.orders') }}">
                        <input type="hidden" class="form-control" name="dateFrom" value="{{\Carbon\Carbon::today()->toDateString()}}">

                        <input type="hidden" name="dateTo" class="form-control" value="{{\Carbon\Carbon::today()->addDay()->toDateString()}}">

                        <button form="report_to_day" type="submit" class="btn btn-success form-control" id="btn-result">
                            <p>Сегодня</p>
                        </button>
                    </form>
                </div>
                <div class="col-md-2">
                    <form id="report_yesterday" method="get" action="{{ route('reports.operators.orders') }}">
                        <input type="hidden" class="form-control" name="dateFrom" value="{{\Carbon\Carbon::today()->subDay(1)->toDateString()}}">

                        <input type="hidden" name="dateTo" class="form-control" value="{{\Carbon\Carbon::today()->toDateString()}}">

                        <button form="report_yesterday" type="submit" class="btn btn-info form-control" id="btn-result">
                            <p>Вчера</p>
                        </button>
                    </form>
                </div>
                <div class="col-md-2">
                    <form id="report_week" method="get" action="{{ route('reports.operators.orders') }}">
                        <input type="hidden" class="form-control" name="dateFrom" value="{{\Carbon\Carbon::today()->subDay(7)->toDateString()}}">

                        <input type="hidden" name="dateTo" class="form-control" value="{{\Carbon\Carbon::today()->addDay()->toDateString()}}">

                        <button form="report_week" type="submit" class="btn btn-warning form-control" id="btn-result">
                            <p>Неделя</p>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body table-responsive" style="overflow-x: visible;">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <th>Оператор</th>
                        <th>Кол-во заказов</th>
                        <th>Сумма общ</th>
                        <th>Сумма тов</th>
                        <th>Сумма акс</th>
                        @foreach($statuses as $status)
                            <th class="bg-{{$status->color}}">{{$status->status}}</th>
                        @endforeach
                    </tr>
                    @foreach($operators as $operator)
                        <tr class="dropdown">
                            <td>{{$operator->name}}</td>
                            <td >
                                <div class="margin">
                                    <div class="btn-group">
                                        <input type="button"
                                               class="btn btn-default btn-call-info"
                                               data-toggle="dropdown"
                                               aria-haspopup="true"
                                               aria-expanded="false"
                                               value="{{$operator->count_orders}}">
                                        <ul class="dropdown-menu" style="min-width: 1500%;">
                                            <li role="presentation">
                                                @foreach($operator->orders->pluck('id') as $orderId)
                                                    <a href="{{route('orders.edit', $orderId)}}"
                                                       target="_blank"
                                                       style="padding: 3px 5px; display: inline-block">
                                                        {{$orderId}}
                                                    </a>
                                                @endforeach
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                            <td>{{$operator->sum_main_product + $operator->sum_acc}}</td>
                            <td>{{$operator->sum_main_product}}</td>
                            <td>{{$operator->sum_acc}} {{$operator->count_acc}}шт.</td>
                            @for($k=0; $k < $statuses->count(); $k++)
                                @if($idStatusConfirm == $statuses[$k]->id)
                                    <td class="bg-{{$statuses[$k]->color}}">
                                        <div class="margin">
                                            <div class="btn-group">
                                                <input type="button"
                                                       class="btn btn-default btn-call-info"
                                                       data-toggle="dropdown"
                                                       aria-haspopup="true"
                                                       aria-expanded="false"
                                                       value="{{ $operator->statuses_group[$statuses[$k]->id] ?? 0}}">
                                                @if($operator->statuses_group[$statuses[$k]->id] ?? false)
                                                    <br/>
                                                    <span>
                                                        {{$mains['main_avg_status'][$statuses[$k]->id][] = round($operator->statuses_group[$statuses[$k]->id] / $operator->count_orders * 100, 1)}}%
                                                    </span>
                                                @endif
                                                <ul class="dropdown-menu" style="min-width: 1500%;">
                                                    <li role="presentation">
                                                        @foreach($operator->orders as $order)
                                                            @if($order->status_id == $idStatusConfirm)
                                                                <a href="{{route('orders.edit', $order->id)}}"
                                                                   target="_blank"
                                                                   style="padding: 3px 5px; display: inline-block">
                                                                    {{$order->id}}
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    <td class="bg-{{$statuses[$k]->color}}">
                                        {{ $operator->statuses_group[$statuses[$k]->id] ?? 0}}
                                        @if($operator->statuses_group[$statuses[$k]->id] ?? false)
                                            <br/>
                                            <span>
                                                {{$mains['main_avg_status'][$statuses[$k]->id][] = (round($operator->statuses_group[$statuses[$k]->id] / $operator->count_orders * 100, 1))}}%
                                            </span>
                                        @endif
                                    </td>
                                @endif
                            @endfor
                        </tr>
                    @endforeach
                    <tr>
                        <th>Итого:</th>
                        <th>{{$mains['main_count_orders']}}</th>
                        <th>{{$mains['main_sum_all']}}</th>
                        <th>{{$mains['main_sum_product']}}</th>
                        <th>{{$mains['main_sum_acc']}}</th>
                        @foreach($statuses as $status)
                            <th class="bg-{{$status->color}}">{{$mains["main_statuses"][$status->id] ?? 0}} <br/>
                                {{($mains['main_avg_status'][$status->id] ?? false) ?
                                    round(array_sum($mains['main_avg_status'][$status->id]) / count($mains['main_avg_status'][$status->id]), 1) . '%'
                                    : 0
                                 }}
                            </th>
                        @endforeach
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection