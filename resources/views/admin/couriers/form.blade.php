@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Админ панель
            <small>Курьеры</small>
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
                {{ isset($courier) ? 'Редактирование курьера' : 'Создание курьера' }}
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
                isset($courier) ? route('couriers.update', $courier->id) : route('couriers.store')
            }}">
                {{ csrf_field() }}

                @if (isset($courier))
                    {{ method_field('PUT') }}
                @endif

                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">ФИО</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" placeholder="ФИО" value="{{ old('name', $courier->name ?? '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="birth_day" class="col-sm-2 control-label">Дата рождения</label>

                    <div class="col-sm-10">
                        <input type="date" class="form-control" name="birth_day"  value="{{ old('birth_day', isset($courier) && $courier->birth_day ? $courier->birth_day->toDateString() : '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="passport_number" class="col-sm-2 control-label">Номер паспорта</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="passport_number"  value="{{ old('passport_number', $courier->passport_number ?? '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="passport_date" class="col-sm-2 control-label">Дата выдачи паспорта</label>

                    <div class="col-sm-10">
                        <input type="date" class="form-control" name="passport_date"  value="{{ old('passport_date', isset($courier) && $courier->passport_date  ? $courier->passport_date->toDateString() : '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="passport_issued_by" class="col-sm-2 control-label">Кем выдан</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="passport_issued_by"  value="{{ old('passport_issued_by', $courier->passport_issued_by ?? '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="passport_address" class="col-sm-2 control-label">Адрес проживания</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="passport_address"  value="{{ old('passport_address', $courier->passport_address ?? '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Описание</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="description" placeholder="Описание" value="{{ old('description', $courier->description ?? '') }}">
                    </div>
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

    </script>
@endpush