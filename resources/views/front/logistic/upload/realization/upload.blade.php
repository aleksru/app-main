@php
/**
 * @var $file \Symfony\Component\Finder\SplFileInfo
 */
@endphp

@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Загрузка реализаций
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>
    </section>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
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
{{--                <div class="alert alert-warning">--}}
{{--                    <ul>--}}
{{--                        <li>Имя файла прайс-листа должно иметь формат: ТипПрайсЛиста_ИмяФайла.xls/xlsx</li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
                 <div class="col-md-12">
                     <div class="box box-info">
                         <div class="box-header with-border">
                             <h3 class="box-title">Загрузка реализаций</h3>
                         </div>
                         <form class="form-horizontal"
                               action="{{route('logistics.uploads.realizations.upload')}}"
                               method="POST"
                               enctype="multipart/form-data"
                         >
                             {{ csrf_field() }}
                             <div class="box-body">
                                 <div class="form-group">
                                     <label for="inputEmail3" class="col-sm-2 control-label">Файл</label>

                                     <div class="col-sm-10">
                                         <input type="file" name="file" class="form-control" placeholder="Выберите файл">
                                     </div>
                                 </div>
                             </div>
                             <div class="box-footer">
                                 <button type="submit" class="btn btn-info pull-right">Отправить</button>
                             </div>
                         </form>
                     </div>
                </div>
                <div class="col-md-12">
                    <div class="box box-warning">
                        <div class="box-header">
                            <h3 class="box-title">Логи</h3>
                        </div>
                        <div class="box-body">
                            @foreach($files as $file)
                                <a target="_blank" href="{{ route('logistics.uploads.realizations.log', ['file' => $file->getFilename()])}}">{{$file->getFilename()}}</a><br/>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box box-warning">
                        <div class="box-header">
                            <h3 class="box-title">Файлы</h3>
                        </div>
                        <div class="box-body">
                            @include('datatable.datatable',[
                                'id' => 'files-table',
                                'route' => route('logistics.uploads.realizations.datatable'),
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
                                    'created_at' => [
                                        'name' => 'Загружен',
                                        'width' => '10%',
                                    ]
                                ],
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        //инициализация таблицы
        $('#files-table').on( 'init.dt', function () {
            setInterval(function () {
                $('#files-table').DataTable().ajax.reload(null, false);
            }, 10000);
        });
    </script>
@endpush
