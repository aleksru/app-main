@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Админ панель
            <small>Юр лицо доставка</small>
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

        <div class="box-header">
        </div>

        <div class="box-body">
            <form id="corp-form" role="form" method="post" class="form-horizontal" action="{{route('admin.delivery-info.store')}}">
                {{ csrf_field() }}
                @foreach( \App\Enums\DeliveryInfoEnums::getConstants() as $corpInfo)
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">{{$corpInfo['name']}}</label>

                        <div class="col-sm-10">
                            <input type="text"
                                   class="form-control"
                                   name="{{$corpInfo['key']}}" placeholder="{{$corpInfo['name']}}"
                                   value="{{ old($corpInfo['key'], setting($corpInfo['key']) ?? '') }}">
                        </div>
                    </div>
                @endforeach
            </form>
            <div class="box-footer">
                <button form="corp-form" type="submit" class="btn btn-primary pull-right">
                    <i class="fa fa-save"></i> Сохранить
                </button>
            </div>
        </div>
    </div>
@endsection
