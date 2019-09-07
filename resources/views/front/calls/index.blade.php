@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Пропущенные звонки
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
    <div class="box-header">

    </div>

    <div class="box box-warning">
        <div class="box-body">
            <missed-calls :operator='@json($operator)'></missed-calls>
        </div>
        <!-- /.box-body -->
    </div>
@endsection
