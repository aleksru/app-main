@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Админ панель
            <small>Статусы заказа</small>
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
                Редактирование статуса
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

            <form id="user-form" role="form" method="post" class="form-horizontal"
                  action="{{route('admin.other-statuses.update', $otherStatus->id)}}">
                {{ csrf_field() }}

                {{ method_field('PUT') }}

                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Название</label>

                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="name" placeholder="Название" value="{{ old('name', $otherStatus->name) }}">
                        <input type="text" class="form-control" name="type"  value="{{ $otherStatus->type }}" style="display: none">
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Сортировка</label>

                    <div class="col-sm-4">
                        <input type="number" class="form-control" name="ordering" value="{{ old('ordering', $otherStatus->ordering) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Цвет</label>

                    <div class="col-sm-4">
                        <select class="form-control" name="color">
                            <option value="" class="">Нет</option>
                            @foreach(get_class_colors() as $color)
                                <option value="{{ $color }}" class="bg-{{$color}}" @if($color == $otherStatus->color) selected @endif>{{$color}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Роль статистики</label>

                    <div class="col-sm-4">
                        <select class="form-control" name="result">
                            <option value="" class="">Нет</option>
                            @foreach(\App\Enums\StatusResults::getStatusesWithDescription() as $status)
                                <option value="{{ $status['value'] }}" @if($status['value'] === $otherStatus->result) selected @endif>{{$status['label']}}</option>
                            @endforeach
                        </select>
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