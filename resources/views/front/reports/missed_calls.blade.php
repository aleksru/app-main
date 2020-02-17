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
            <!-- /.box-header -->
            <div class="box-body table-responsive" style="overflow-x: visible;">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <th>Дата</th>
                        <th>Кол-во</th>
                        <th>Заказы</th>
                    </tr>
                    @foreach($orders as $date => $ordersLoc)
                        <tr>
                            <td style="width: 110px">{{$date}}</td>
                            <td style="width: 70px">{{$ordersLoc->count()}}</td>
                            <td>
                                @foreach($ordersLoc as $order)
                                    <a href="{{route('orders.edit', $order->id)}}"
                                       class="badge bg-{{$order->status ? $order->status->color : ''}}"
                                       title="{{$order->status ? $order->status->status : ''}}"
                                       target="_blank"
                                       style="padding: 3px 5px; display: inline-block">
                                        {{$order->id}}
                                    </a>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection