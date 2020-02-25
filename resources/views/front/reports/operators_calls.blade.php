
@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Звонки
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
                @include('front.reports.parts.dates', ['route' => route('reports.operators.calls')])
            </div>
        </div>
        <div class="box box-default">
            <div class="box-body">
                <div class="col-md-12">
                    @include('front.reports.tables.operator_calls_datatable',[
                        'id' => 'table',
                        'route' => route('reports.operators.calls', ['dateFrom' => request('dateFrom'), 'dateTo' => request('dateTo')]),
                        'pageLength' => 100,
                        'columns' => [
                            'name' => [
                                'name' => 'Оператор',
                                'width' => '1%',
                                'searchable' => false,
                            ],
                            'cnt_outgoings' => [
                                'name' => 'Исходящие',
                                'width' => '1%',
                                'searchable' => false,
                            ],
                            'cnt_incomings' => [
                                'name' => 'Входящие',
                                'width' => '1%',
                                'searchable' => false,
                            ],
                            'avg_talk' => [
                                'name' => 'Ср время ответа (сек)',
                                'width' => '1%',
                                'searchable' => false,
                            ],
                            'avg_call_time' => [
                                'name' => 'Ср время разговора (сек)',
                                'width' => '1%',
                                'searchable' => false,
                            ],
                        ],
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection


