@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Админ панель
            <small>Тексты</small>
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
            <h3>Текст счета</h3>
        </div>

        <div class="box-body">
            <form id="invoice" role="form" method="post" class="form-horizontal" action="{{route('admin.warranty-text.store')}}">
                {{ csrf_field() }}
                <input type="hidden" name="type" value="{{\App\Enums\TextTypeEnums::VOUCHER}}">
                @component('richeditor', [
                    'id' => 'voucher-id',
                    'name' => 'content',
                    'label' => 'content'
                ])
                    {!! setting(\App\Enums\TextTypeEnums::VOUCHER_FULL)  !!}
                @endcomponent

            </form>
            <div class="box-footer">
                <button form="invoice" type="submit" class="btn btn-primary pull-right">
                    <i class="fa fa-save"></i> Сохранить
                </button>
            </div>
        </div>
    </div>

    <div class="box box-warning">

        <div class="box-header">
            <h3>Текст доставка</h3>
        </div>

        <div class="box-body">
            <form id="delivery" role="form" method="post" class="form-horizontal" action="{{route('admin.warranty-text.store')}}">
                {{ csrf_field() }}
                <input type="hidden" name="type" value="{{\App\Enums\TextTypeEnums::DELIVERY}}">
                @component('richeditor', [
                    'id' => 'delivery-id',
                    'name' => 'content',
                    'label' => 'content'
                ])
                    {!! setting(\App\Enums\TextTypeEnums::DELIVERY_FULL)  !!}
                @endcomponent
            </form>
            <div class="box-footer">
                <button form="delivery" type="submit" class="btn btn-primary pull-right">
                    <i class="fa fa-save"></i> Сохранить
                </button>
            </div>
        </div>
    </div>
@endsection

