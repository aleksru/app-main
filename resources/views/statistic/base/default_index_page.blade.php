<?php
/**
 * @var App\Services\Statistic\Abstractions\BaseReportTableRender $DTRender
 */
?>

@extends('layouts.adminlte.app')

@section('content')
    {!! $DTRender->renderSingleReport() !!}
@endsection