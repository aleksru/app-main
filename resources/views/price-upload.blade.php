@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Загрузка прайс-листа
            {{--<small>Optional description</small>--}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>
    </section>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (Session::get('message'))
                <div class="alert alert-success" role="alert">
                  <strong>{{Session::get('message')}}</strong>
                </div>
            @endif
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
            @endif
            <div class="alert alert-warning">
              <ul>
                  <li>Имя файла прайс-листа должно иметь формат: ТипПрайсЛиста_ИмяФайла.xls/xlsx</li>
                  <li>Доступные типы прайс-листа:
                       @forelse($priceLists as $priceList)
                         {{ $priceList->name }},
                       @empty
                         Нет доступных прайс-листов
                       @endforelse
                  </li>
                  <li>Обязательные столбцы для заполнения:
                                    {{\App\Product::PRICE_LIST_ARTICUL}},
                                    {{\App\Product::PRICE_LIST_PRODUCT}},
                                    {{\App\Product::PRICE_LIST_PRICE}}
                  </li>
              </ul>
            </div>
            <div class="card">
                <form action="{{route('upload-price')}}" method="POST" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <div class="form-group">
                    <label for="exampleFormControlFile1">Загрузка прайс листа</label>
                    <input type="file" class="form-control-file" name="file">
                  </div>
                     <button class="btn btn-primary" type="submit">Загрузить</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

