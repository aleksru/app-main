@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Заказы
            <small>Новый заказ</small>
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
                Создание клиента
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

            <form id="user-form" role="form" method="post" class="form-horizontal" action="{{ route('create.user-order') }}">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Телефон</label>

                    <div class="col-sm-6">
                        <input type="text" class="form-control"  value="" name="phone">
                    </div>
                </div>

                <div class="col-sm-8">
                    <button form="user-form" type="submit" class="btn btn-primary pull-right">
                        <i class="fa fa-save"></i> Сохранить
                    </button>

                </div>
            </form>
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