@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Админ панель
            <small>Магазины</small>
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
                {{ isset($store) ? 'Редактирование магазина' : 'Создание магазина' }}
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
                isset($store) ? route('admin.stores.update', $store->id) : route('admin.stores.store')
            }}">
                {{ csrf_field() }}

                @if (isset($store))
                    {{ method_field('PUT') }}
                @endif

                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Название</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" placeholder="Название" value="{{ old('name', $store->name ?? '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Телефон</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="phone" placeholder="Телефон" value="{{ old('phone', $store->phone ?? '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="address" class="col-sm-2 control-label">Адрес</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="address" placeholder="Адрес" value="{{ old('address', $store->address ?? '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-sm-2 control-label">Описание</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="description" placeholder="Описание" value="{{ old('description', $store->description ?? '') }}">
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