@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Админ панель
            <small>Логисты</small>
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
            <h3 class="box-title">
                {{ isset($logist) ? 'Редактирование пользователя' : 'Создание пользователя' }}
            </h3>
        </div>

        <div class="box-body">

            @if ($errors->any())
                <div class="callout callout-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            @endif

            <form id="user-form" role="form" method="post" class="form-horizontal" action="{{
                isset($logist) ? route('admin.logists.update', [$logist->id]) : route('admin.logists.store')
            }}">
                {{ csrf_field() }}

                @if (isset($logist))
                    {{ method_field('PUT') }}
                @endif

                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">ФИО</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" placeholder="ФИО" value="{{ old('name', $logist->name ?? '') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-sm-2 control-label">Города доставки</label>

                    <select class="col-sm-10 js-example-basic-multiple" name="cities[]" multiple="multiple">
                        @if (isset($logist))
                            @foreach ($logist->cities as $city)
                                <option value="{{ $city->id }}" selected>{{ $city->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </form>
        </div>
        <div class="box-footer">
            <button form="user-form" type="submit" class="btn btn-primary pull-right">
                <i class="fa fa-save"></i> Сохранить
            </button>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        @if (session()->has('success'))
            toast.success('{{ session()->get('success') }}')
        @endif
        @if (session()->has('error'))
            toast.error('{{ session()->get('error') }}')
        @endif
        let cities = {!!   json_encode(\App\Models\City::select('id', 'name as text')->get()->toArray()) !!}
        $(function() {
                $('.js-example-basic-multiple').select2({
                    data: cities
                });
        });
    </script>
@endpush