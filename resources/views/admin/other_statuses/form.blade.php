@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Админ панель
            <small>Статусы</small>
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
                Список статусов
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

            <div class="col-md-12">
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="{{$activeNav == \App\Enums\OtherStatusEnums::SUBSTATUS_TYPE ? 'active' : ''}}">
                            <a href="#tab_1" data-toggle="tab" aria-expanded="{{$activeNav == \App\Enums\OtherStatusEnums::SUBSTATUS_TYPE ? 'true' : 'false'}}">Подстатусы</a>
                        </li>
                        <li class="{{$activeNav == \App\Enums\OtherStatusEnums::STOCK_TYPE ? 'active' : ''}}">
                            <a href="#tab_2" data-toggle="tab" aria-expanded="{{$activeNav == \App\Enums\OtherStatusEnums::STOCK_TYPE ? 'true' : 'false'}}">Склад</a>
                        </li>
                        <li class="{{$activeNav == \App\Enums\OtherStatusEnums::LOGISTIC_TYPE ? 'active' : ''}}">
                            <a href="#tab_3" data-toggle="tab" aria-expanded="{{$activeNav == \App\Enums\OtherStatusEnums::LOGISTIC_TYPE ? 'true' : 'false'}}">Логистика</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane {{$activeNav == \App\Enums\OtherStatusEnums::SUBSTATUS_TYPE ? 'active' : ''}}" id="tab_1">
                            <div class="row">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Список подстатусов</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table class="table table-bordered" id="table-prices">
                                        <tbody>
                                            <tr>
                                                <th style="width: 10px">#</th>
                                                <th>Название</th>
                                                <th>Сортировка</th>
                                                <th>Действия</th>
                                            </tr>
                                            @foreach ($subStatuses as $subStatus)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $subStatus->name }}</td>
                                                    <td>{{ $subStatus->ordering }}</td>
                                                    <td>
                                                    @include('datatable.actions', [
                                                        'edit' => [
                                                            'route' => null,
                                                        ],
                                                        'delete' => [
                                                        'id' => $subStatus->id,
                                                        'name' =>  $subStatus->name,
                                                        'route' => route('admin.other-statuses.destroy', $subStatus->id)
                                                        ]
                                                    ])
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <form id="substatus-form" role="form" method="post" class="form-horizontal" action="{{route('admin.other-statuses.store')}}">
                                        {{ csrf_field() }}

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Название</label>

                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" name="name" placeholder="Название" value="{{ old('name') }}">
                                                <input type="text" class="form-control" name="type"  value="{{ \App\Enums\OtherStatusEnums::SUBSTATUS_TYPE }}" style="display: none">
                                            </div>
                                        </div>

                                    </form>
                                </div>
                                <div class="box-footer">
                                    <button form="substatus-form" type="submit" class="btn btn-primary pull-right">
                                        <i class="fa fa-save"></i> Добавить
                                    </button>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer clearfix">
                                </div>
                            </div>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane {{$activeNav == \App\Enums\OtherStatusEnums::STOCK_TYPE ? 'active' : ''}}" id="tab_2">
                            <div class="row">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Список статусов склада</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table class="table table-bordered" id="table-prices">
                                        <tbody>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>Название</th>
                                            <th>Цвет</th>
                                            <th>Сортировка</th>
                                            <th>Действия</th>
                                        </tr>
                                        @foreach ($stockStatuses as $stockStatus)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $stockStatus->name }}</td>
                                                <td>
                                                    <span class="badge bg-{{$stockStatus->color}}" style="padding: 10px;">
                                                    {{ $stockStatus->color }}
                                                    </span>
                                                </td>
                                                <td>{{ $stockStatus->ordering }}</td>
                                                <td>
                                                    @include('datatable.actions', [
                                                        'edit' => [
                                                            'route' => route('admin.other-statuses.edit', $stockStatus->id),
                                                        ],
                                                        'delete' => [
                                                        'id' => $stockStatus->id,
                                                        'name' =>  $stockStatus->name,
                                                        'route' => route('admin.other-statuses.destroy', $stockStatus->id)
                                                        ]
                                                    ])
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <form id="stockstatus-form" role="form" method="post" class="form-horizontal" action="{{route('admin.other-statuses.store')}}">
                                        {{ csrf_field() }}

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Название</label>

                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="name" placeholder="Название" value="{{ old('name') }}">
                                                <input type="text" class="form-control" name="type"  value="{{ \App\Enums\OtherStatusEnums::STOCK_TYPE }}" style="display: none">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Цвет</label>

                                            <div class="col-sm-4">
                                                @include('admin.parts.select_color', ['name' => 'color'])
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="box-footer">
                                    <button form="stockstatus-form" type="submit" class="btn btn-primary pull-right">
                                        <i class="fa fa-save"></i> Добавить
                                    </button>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer clearfix">
                                </div>
                            </div>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane {{$activeNav == \App\Enums\OtherStatusEnums::LOGISTIC_TYPE ? 'active' : ''}}" id="tab_3">
                            <div class="row">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Список статусов логистики</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table class="table table-bordered" id="table-prices">
                                        <tbody>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>Название</th>
                                            <th>Сортировка</th>
                                            <th>Цвет</th>
                                            <th>Действия</th>
                                        </tr>
                                        @foreach ($logisticStatuses as $logisticStatus)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $logisticStatus->name }}</td>
                                                <td>{{ $logisticStatus->ordering }}</td>
                                                <td>
                                                    <span class="badge bg-{{$logisticStatus->color}}" style="padding: 10px;">
                                                    {{ $logisticStatus->color }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @include('datatable.actions', [
                                                        'edit' => [
                                                            'route' => route('admin.other-statuses.edit', $logisticStatus->id),
                                                        ],
                                                        'delete' => [
                                                        'id' => $logisticStatus->id,
                                                        'name' =>  $logisticStatus->name,
                                                        'route' => route('admin.other-statuses.destroy', $logisticStatus->id)
                                                        ]
                                                    ])
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <form id="logisticstatus-form" role="form" method="post" class="form-horizontal" action="{{route('admin.other-statuses.store')}}">
                                        {{ csrf_field() }}

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Название</label>

                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="name" placeholder="Название" value="{{ old('name') }}">
                                                <input type="text" class="form-control" name="type"  value="{{ \App\Enums\OtherStatusEnums::LOGISTIC_TYPE }}" style="display: none">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Цвет</label>

                                            <div class="col-sm-4">
                                                @include('admin.parts.select_color', ['name' => 'color'])
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="box-footer">
                                    <button form="logisticstatus-form" type="submit" class="btn btn-primary pull-right">
                                        <i class="fa fa-save"></i> Добавить
                                    </button>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer clearfix">
                                </div>
                            </div>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- nav-tabs-custom -->
            </div>
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

    $('.tab-content').on('click', '.btn-delete', function () {
        let id      = $(this).data('id');
        let name    = $(this).data('name');
        let route   = $(this).data('route');

        let toastID = 'toast-delete-' + id;

        if ($('#' + toastID).length > 0)
            return false;

        toast.confirm('Вы действительно хотите удалить элемент "' + name + '"?', function () {
            let loading = toast.loading('Идет удаление "' + name + '"');
            axios.delete(route)
                .then((response) => {
                toast.hide(loading);
            toast.success(response.data.message);
            location.reload();
        })
            .catch((error) => {
                toast.hide(loading);
            toast.error('Ошибка сервера! Пожалуйста, свяжитесь с администратором.');
        })
        }, null, { id: toastID });
    });

</script>
@endpush