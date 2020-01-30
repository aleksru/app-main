@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Админ панель
            <small>Чат</small>
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
                {{ isset($chat) ? 'Редактирование чата' : 'Создание чата' }}
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
                isset($chat) ? route('admin.chats.update', [$chat->id]) : route('admin.chats.store')
            }}">
                {{ csrf_field() }}

                @if (isset($chat))
                    {{ method_field('PUT') }}
                @endif

                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Название</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" placeholder="Название" value="{{ old('name', $chat->name ?? '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Группы</label>
                    <div class="col-sm-10">
                        <select class="js-example-groups-multi form-control" name="groups[]" multiple="multiple">
                            @if (isset($chat))
                                @foreach ($chat->groups as $group)
                                    <option value="{{ $group->id }}" selected>{{ $group->description }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Пользователи</label>
                    <div class="col-sm-10">
                        <select class="js-example-users-multi form-control" name="users[]" multiple="multiple">
                            @if (isset($chat))
                                @foreach ($chat->users as $user)
                                    <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                                @endforeach
                            @endif
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
    let groups = {!! json_encode(\App\Models\UserGroup::selectRaw('id, description as text')->get()->toArray()) !!}
        $(function() {
            $('.js-example-groups-multi').select2({
                data: groups,
                allowClear: true,
                placeholder: "Выберите группы...",
            });
        });
    let users = {!! json_encode(\App\User::selectRaw('id, name as text')->get()->toArray()) !!}
        $(function() {
        $('.js-example-users-multi').select2({
            data: users,
            allowClear: true,
            placeholder: "Выберите пользователей...",
        });
    });
</script>
@endpush