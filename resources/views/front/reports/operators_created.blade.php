
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
                <h3>Созданные заказы</h3>
            </div>
            <div class="box-header with-border">

            </div>
            <div class="box-body">
                <form id="report" method="get" action="{{ route('reports.operators.created') }}">
                    <div class="row">
                        <div class="col-md-2">
                            <label>Дата начала</label>
                            <input type="date" class="form-control" name="dateFrom" id="dateFrom" required>
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
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <th>Оператор</th>
                        <th>Кол-во заказов</th>
                        <th>Сумма</th>
                        @foreach($statuses as $status)
                            <th class="bg-{{$status->color}}">{{$status->status}}</th>
                        @endforeach
                    </tr>
                    @foreach($results as $result)
                        <tr>
                            <td>{{$result['operator']}}</td>
                            <td>{{$result['count']}}</td>
                            <td>{{$result['sum']}}</td>
                            @for($k=0; $k < $statuses->count(); $k++)
                                <td class="bg-{{$statuses[$k]->color}}">
                                    {{ $result['statuses'][$statuses[$k]->id] ?? 0}}
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection