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
                <h3>Пропущенные звонки</h3>
            </div>
            <div class="box-header with-border">

            </div>
            <div class="box-body">
                @include('front.reports.parts.dates', ['route' => route('reports.missed_calls')])
            </div>
        </div>
        <div class="box">
            <div class="box-body table-responsive" style="overflow-x: visible;">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th>Оператор</th>
                            <th>Кол-во заказов</th>
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
                                                   value="{{$operator->orders->count()}}">
                                        </div>
                                    </div>
                                </td>
                                @for($k=0; $k < $statuses->count(); $k++)
                                    <td class="bg-{{$statuses[$k]->color}}">
                                        <div class="margin">
                                            <div class="btn-group">
                                                <input type="button"
                                                       class="btn btn-default btn-call-info"
                                                       data-toggle="dropdown"
                                                       aria-haspopup="true"
                                                       aria-expanded="false"
                                                       value="{{ ($operator->orders_group[$statuses[$k]->id] ?? false) ? ($operator->orders_group[$statuses[$k]->id])->count() : 0}}">
                                                <br/>
                                                {{isset($operator->orders_group[$statuses[$k]->id]) && $operator->orders_group[$statuses[$k]->id]->count() > 0 ?
                                                        round($operator->orders_group[$statuses[$k]->id]->count() / $operator->orders->count() * 100, 1) : 0}}%
                                                <ul class="dropdown-menu" style="min-width: 1500%;">
                                                    <li role="presentation">
                                                        @foreach(($operator->orders_group[$statuses[$k]->id] ?? []) as $order)
                                                            <a href="{{route('orders.edit', $order->id)}}"
                                                               target="_blank"
                                                               style="padding: 3px 5px; display: inline-block">
                                                                {{$order->id}}
                                                            </a>
                                                        @endforeach
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                        <tr>
                            <th>Итого:</th>
                            <th>{{$mains['count_orders']}}</th>
                            @foreach($statuses as $status)
                                <th class="bg-{{$status->color}}">
                                    {{$mains['count_statuses'][$status->id] ?? 0}} <br/>
                                    {{isset($mains['count_statuses'][$status->id]) && $mains['count_statuses'][$status->id] > 0 ?
                                    round($mains['count_statuses'][$status->id] / $mains['count_orders'] * 100, 1) : 0}}%
                                </th>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection