@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Админ панель
            <small>Время доставки</small>
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

            <periods-form :initial_data="{{ json_encode($periods) }}"></periods-form>
        </div>
        <div class="box-footer">
            <button form="user-form" type="submit" class="btn btn-primary pull-right">
                <i class="fa fa-save"></i> Сохранить
            </button>
        </div>
    </div>
    <div class="box box-warning">
        <div class="box-header">
            <h3 class="box-title">
                Прочие типы доставки
            </h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered" id="table-other-deliveries">
                <tbody>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Название</th>
                    <th>Действия</th>
                </tr>
                @foreach ($otherPeriods as $period)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $period->name }}</td>
                        <td>
                            @include('datatable.actions', [
                                'delete' => [
                                    'id' => $period->id,
                                    'name' =>  $period->name,
                                    'route' => route('admin.other-delivery.destroy', $period->id)
                                ]
                            ])
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="row">
            <form id="other_deliveries_form" role="form" method="post" class="form-horizontal" action="{{route('admin.other-delivery.create')}}">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Название</label>

                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="name" placeholder="Название" value="{{ old('name') }}">
                    </div>
                </div>
            </form>
            <div class="box-footer">
                <button form="other_deliveries_form" type="submit" class="btn btn-primary pull-right">
                    <i class="fa fa-save"></i> Добавить
                </button>
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

    $(function () {
        $('#table-other-deliveries').on('click', '.btn-delete', function () {
            let id      = $(this).data('id');
            let name    = $(this).data('name');
            let route   = $(this).data('route');

            let toastID = 'toast-delete-' + id;

            if ($('#' + toastID).length > 0)
                return false;

            toast.confirm('Вы действительно хотите удалить элемент "' + name + '"?', () => {
                let loading = toast.loading('Идет удаление "' + name + '"');
                axios.delete(route)
                    .then((response) => {
                        toast.hide(loading);
                        toast.success(response.data.message);
                        this.parentElement.parentElement.remove();
                    })
                    .catch((error) => {
                        toast.hide(loading);
                        toast.error('Ошибка сервера! Пожалуйста, свяжитесь с администратором.');
                    })
            }, null, { id: toastID });
        });
    });
</script>
@endpush