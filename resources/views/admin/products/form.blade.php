@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Админ панель
            <small>Товары</small>
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
                {{ isset($product) ? 'Редактирование товара' : 'Создание товара' }}
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
                isset($product) ? '' : route('admin.products.store')
            }}">
                {{ csrf_field() }}

                @if (isset($product))
                    {{ method_field('PUT') }}
                @endif

                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Название</label>

                    <div class="col-sm-10">
                        <input type="text"
                               class="form-control"
                               name="product_name"
                               placeholder="Название"
                               value="{{ old('product_name', $product->product_name ?? '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-sm-2 control-label">Тип</label>

                    <div class="col-sm-10">
                        <select name="type">
                            <option value="">НЕТ</option>
                            @foreach(\App\Enums\ProductType::getConstantsForDescription() as $type)
                                <option value="{{$type['name']}}">{{$type['desc']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-sm-2 control-label">Категория</label>

                    <div class="col-sm-10">
                        <select name="category">
                            <option value="">НЕТ</option>
                            @foreach(\App\Enums\ProductCategoryEnums::getConstantsForDescription() as $category)
                                <option value="{{$category['name']}}">{{$category['desc']}}</option>
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
