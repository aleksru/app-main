@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Админ панель
            <small>Пользователи</small>
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
                {{ isset($user) ? 'Редактирование пользователя' : 'Создание пользователя' }}
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
                isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store')
            }}">
                {{ csrf_field() }}

                @if (isset($user))
                    {{ method_field('PUT') }}
                @endif

                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Login</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" placeholder="Login" value="{{ old('name', $user->name ?? '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="email" placeholder="Email" value="{{ old('email', $user->email ?? '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-sm-2 control-label">ФИО</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="description" placeholder="Описание" value="{{ old('description', $user->description ?? '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="col-sm-2 control-label">Пароль</label>

                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="password" placeholder="Пароль">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="col-sm-2 control-label">Права</label>

                    <select class="col-sm-10 js-example-basic-multiple" name="roles[]" multiple="multiple">
                        @if (isset($user))
                            @foreach ($user->roles as $role)
                                <option value="{{ $role->id }}" selected>{{ $role->description }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label for="group" class="col-sm-2 control-label">Группа</label>

                    <select class="col-sm-10 js-group-basic-single" name="group_id">
                        @if ($user->group)
                            <option value="{{ $user->group_id }}" selected>{{ $user->group->description }}</option>
                        @endif
                        <option value="{{ null }}">Нет</option>
                    </select>
                </div>
                @if($optionsAccount)
                    <div class="form-group">
                        <label for="group" class="col-sm-2 control-label">Профиль</label>

                        <select class="col-sm-10 js-profile-basic-single" name="account_id">
                            @if ($user->account)
                                <option value="{{ $user->account->id ?? '' }}" selected>{{ $user->account->name ?? '' }}</option>
                            @endif
                            <option value="{{ null }}">Нет</option>
                        </select>
                    </div>
                @endif
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

        let roles = {!!   json_encode(\App\Role::select('id', 'description as text')->get()->toArray()) !!}
        $(function() {
            $('.js-example-basic-multiple').select2({
                data: roles
            });
        });

        let groups = {!!   json_encode(\App\Models\UserGroup::select('id', 'description as text')->get()->toArray()) !!}
        $(function() {
            $('.js-group-basic-single').select2({
                data: groups,
                allowClear: true,
                placeholder: "Выберите группу...",
            });
        });

        @if($optionsAccount)
            let optionsAccount = {!! json_encode($optionsAccount::select('id', 'name as text')->get()->toArray()) !!}
            $(function() {
                    $('.js-profile-basic-single').select2({
                        data: optionsAccount,
                        allowClear: true,
                        placeholder: "Выберите профиль...",
                    });
            });
        @endif
    </script>
@endpush