@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Админ панель
            <small>Операторы</small>
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
                {{ isset($operator) ? 'Редактирование оператора' : 'Создание оператора' }}
            </h3>
        </div>
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="@if(!$isActiveJobTimeNav) active @endif">
                    <a href="#tab_1"
                       data-toggle="tab"
                       aria-expanded="@if(!$isActiveJobTimeNav) true @else false @endif"
                    >Основное</a>
                </li>
                <li class="@if($isActiveJobTimeNav) active @endif">
                    <a href="#tab_2"
                       data-toggle="tab"
                       aria-expanded="@if($isActiveJobTimeNav) true @else false @endif"
                    >Рабочее время</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane @if(!$isActiveJobTimeNav) active @endif" id="tab_1">
                    <div class="box-body">

                        @if ($errors->any())
                            <div class="callout callout-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </div>
                        @endif

                        <form id="user-form" role="form" method="post" class="form-horizontal" action="{{
                            isset($operator) ? route('admin.operators.update', $operator->id) : route('admin.operators.store')
                        }}">
                            {{ csrf_field() }}

                            @if (isset($operator))
                                {{ method_field('PUT') }}
                            @endif

                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">ФИО</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" placeholder="ФИО" value="{{ old('name', $operator->name ?? '') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-2 control-label">sip_login</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="sip_login" placeholder="sip login" value="{{ old('sip_login', $operator->sip_login ?? '') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-2 control-label">Внутренний номер</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="extension"  value="{{ old('extension', $operator->extension ?? '') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="price_type_id" class="col-sm-2 control-label"></label>
                                <div class="col-sm-6">
                                    <div class="pretty p-default p-curve p-thick">
                                        <input type="hidden" value="0" name="is_disabled" />
                                        <input class="form-control" type="checkbox" value="1" name="is_disabled"
                                               @if(isset($operator) && $operator->is_disabled)checked @endif
                                        />
                                        <div class="state p-danger-o">
                                            <label>Отключен</label>
                                        </div>
                                    </div>
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
                <!-- /.tab-pane -->
                <div class="tab-pane @if($isActiveJobTimeNav) active @endif" id="tab_2">
                    <div class="box-body">
                        @if($jobTime)
                            <div class="row">
                            <div class="box-header with-border">
                                <h3 class="box-title">Список</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Вход</th>
                                        <th>Выход</th>
                                    </tr>
                                    @foreach($jobTime as $item)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$item->logon}}</td>
                                            <td>{{$item->logout}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer clearfix">
                                <ul class="pagination pagination-sm no-margin pull-right">
                                    {{ $jobTime->links() }}
                                </ul>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- /.tab-content -->
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