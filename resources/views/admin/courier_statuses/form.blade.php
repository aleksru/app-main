@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Админ панель
            <small>Статусы курьеров</small>
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
                {{ isset($courierStatus) ? 'Редактирование статуса' : 'Создание статуса' }}
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
                isset($courierStatus) ? route('courier-statuses.update', $courierStatus->id) : route('courier-statuses.store')
            }}">
                {{ csrf_field() }}

                @if (isset($courierStatus))
                    {{ method_field('PUT') }}
                @endif

                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Название</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" placeholder="Название" value="{{ old('name', $courierStatus->name ?? '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="max_sum_order" class="col-sm-2 control-label">Макс сумма</label>

                    <div class="col-sm-10">
                        <input type="number"
                               class="form-control"
                               name="max_sum_order"
                               placeholder="Макс сумма"
                               value="{{ old('max_sum_order', $courierStatus->max_sum_order ?? null) }}">
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