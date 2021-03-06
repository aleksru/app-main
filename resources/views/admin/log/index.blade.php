@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Админ панель
            <small>Лог</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>
    </section>
@endsection
@section('content')
    <div class="col-md-8">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="box box-warning">

        <div class="box-body">
            <div class="form-group">
                <label>Logs</label>
                <textarea class="form-control" rows="20" disabled="">
                    @if ($filePath)
                        {{ file_get_contents($filePath) }}
                    @else
                        Лог отсутствует
                    @endif
                </textarea>
            </div>
        </div>
    </div>
@endsection