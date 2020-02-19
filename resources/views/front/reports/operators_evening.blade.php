
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
                @include('front.reports.parts.dates', ['route' => route('reports.operators.evening')])
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
                        <th>Кол-во оформленных зак-в</th>
                        <th>АКССЫ шт</th>
                        <th>Air Pods шт</th>
                        <th>Mi Band шт</th>
                        <th>Исход звонки</th>
                        <th>Вход звонки</th>
                    </tr>
                    @foreach($operators as $operator)
                        <tr class="dropdown">
                            <td>{{$operator->name}}</td>
                            <td>{{$operator->count_orders}}</td>
                            <td>{{$operator->count_confirms}}</td>
                            <td>{{$operator->count_acc}}</td>
                            <td>{{$operator->count_airpods}}</td>
                            <td>{{$operator->count_mibands}}</td>
                            <td>{{$operator->count_outgoings->cnt ?? 0}}</td>
                            <td>{{$operator->count_incomings->cnt ?? 0}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th>Итого:</th>
                        <th>{{$mains['main_count_orders']}}</th>
                        <th>{{$mains['main_count_confirms']}}</th>
                        <th>{{$mains['main_count_acc']}}</th>
                        <th>{{$mains['main_count_airpods']}}</th>
                        <th>{{$mains['main_count_mibands']}}</th>
                        <th>{{$mains['main_count_outgoings']}}</th>
                        <th>{{$mains['main_count_incomings']}}</th>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection