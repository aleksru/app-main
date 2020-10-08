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
    <div class="row">
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
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="alert alert-warning">
              <ul>
                  <li>
                        Структура загружаемого файла:
                      <ul>
                          <li>Столбец 1 - артикул</li>
                          <li>Столбец 2 - название товара</li>
                          <li>Столбец 3 - цена</li>
                          <li>Столбец 4 - цена по акции (не обязателен к заполнению)</li>
                      </ul>
                  </li>
                  <li>
                      Файл обрабатывается со 2ой строки. Первая строка предполагает заголовки столбцов(не обязательно к заполнению)
                  </li>

              </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Загрузка прайс листа</h3>
                </div>
                <form class="form-horizontal"
                      action="{{route('upload-price')}}"
                      method="POST"
                      enctype="multipart/form-data"
                >
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="form-group">
                            <label for="file" class="col-sm-2 control-label">Файл</label>

                            <div class="col-sm-10">
                                <input type="file" name="file" class="form-control" placeholder="Выберите файл">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="file" class="col-sm-2 control-label">Прайс</label>

                            <div class="col-sm-10">
                                <select class="js-example-price-lists-single form-control" name="price_list_id">
                                    <option value="{{ null }}">  </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-info pull-right">Отправить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="box box-warning">
                <div class="box-header">
                    <h3 class="box-title">Файлы</h3>
                </div>
                <div class="box-body">
                    @include('datatable.datatable_without_exchange',[
                        'id' => 'files-table',
                        'route' => route('product.files.datatable'),
                        'pageLength' => 10,
                        'columns' => [
                            'id' => [
                                'name' => 'ID',
                                'width' => '1%',
                            ],
                            'name' => [
                                'name' => 'Файл',
                                'width' => '10%',
                            ],
                            'status' => [
                                'name' => 'Статус',
                                'width' => '10%',
                            ],
                            'count_processed' => [
                                'name' => 'Кол-во товаров',
                                'width' => '10%',
                            ],
                            'created_at' => [
                                'name' => 'Загружен',
                                'width' => '10%',
                            ]
                        ],
                    ])
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-warning">
                <div class="box-header">
                    <h3 class="box-title">Прайсы</h3>
                </div>
                <div class="box-body">
                    @include('datatable.datatable',[
                        'id' => 'price-list-table',
                        'route' => route('price-lists.datatable'),
                        'pageLength' => 10,
                        'columns' => [
                            'id' => [
                                'name' => 'ID',
                                'width' => '1%',
                            ],
                            'name' => [
                                'name' => 'Прайс',
                                'width' => '10%',
                            ],
                            'version' => [
                                'name' => 'Версия',
                                'width' => '5%',
                            ],
                            'updated_at' => [
                                'name' => 'Последнее обновление',
                                'width' => '10%',
                            ],
                            'actions' => [
                                'name' => 'Перейти',
                                'width' => '5%',
                            ]
                        ],
                    ])
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Магазины</h3>
                </div>

                <div class="box-body">
                    @include('datatable.datatable_without_exchange',[
                    'id' => 'stores-table',
                    'route' => route('admin.stores.datatable'),
                    'pageLength' => 10,
                    'columns' => [
                        'id' => [
                            'name' => 'ID',
                            'width' => '1%',
                            'searchable' => true,
                        ],
                        'name' => [
                            'name' => 'Название',
                            'width' => '10%',
                            'searchable' => true,
                        ],
                        'phone' => [
                            'name' => 'Телефон',
                            'width' => '10%',
                            'searchable' => true,
                        ],

                        'price_list' => [
                            'name' => 'Прайс',
                            'width' => '10%',
                            'searchable' => false,
                        ],
                    ],
                ])
                </div>
                <div class="box-footer">
                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        let priceLists = {!! json_encode(\App\PriceType::select('id', 'name as text')->orderBy('text')->get()->toArray()) !!}
        $(function() {
            $('.js-example-price-lists-single').select2({
                data: priceLists,
                allowClear: false,
                placeholder: "Выберите прайс...",
            });
        });

        //инициализация таблицы
        $('#price-list-table').on( 'init.dt', function () {
            setInterval(function () {
                $('#price-list-table').DataTable().ajax.reload(null, false);
            }, 5000);
        });

        //инициализация таблицы
        $('#files-table').on( 'init.dt', function () {
            setInterval(function () {
                $('#files-table').DataTable().ajax.reload(null, false);
            }, 5000);
        });
    </script>
@endpush


