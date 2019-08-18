@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Массовая смена статуса заказов
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

        </div>
        <div class="box-body">
            <mass-statuses :initial_operator_statuses='@json($operatorStatuses)'
            ></mass-statuses>
        </div>
    </div>
@endsection
