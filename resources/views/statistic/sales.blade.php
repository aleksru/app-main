@extends('layouts.adminlte.app')

@section('content')
        <div class="box">
            <div class="box-header">Продажи\Отказы</div>

            <div class="box-body">
                @include('charts.chart', compact('chart'))
            </div>
        </div>
@endsection

