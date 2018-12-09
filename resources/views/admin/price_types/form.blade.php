@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Админ панель
            <small>Прайс-листы</small>
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
                Список прайс-листов
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

            <div class="row">
                <div class="box-header with-border">
                    <h3 class="box-title">Список</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered" id="table-prices">
                        <tbody>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Название</th>
                                <th>Действия</th>
                            </tr>
                            @foreach (\App\PriceType::all() as $priceType)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $priceType->name }}</td>
                                    <td>
                                        @include('datatable.actions', [
                                            'edit' => [
                                                'route' => null,
                                            ],
                                            'delete' => [
                                                'id' => $priceType->id,
                                                'name' => $priceType->name,
                                                'route' => route('admin.price-types.destroy', $priceType->id)
                                            ]
                                        ])
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                </div>
            </div>

            <div class="row">
                <form id="user-form" role="form" method="post" class="form-horizontal" action="{{route('admin.price-types.store')}}">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Название</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="name" placeholder="Название" value="{{ old('name') }}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="box-footer">
            <button form="user-form" type="submit" class="btn btn-primary pull-right">
                <i class="fa fa-save"></i> Добавить
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

    $('#table-prices').on('click', '.btn-delete', function () {
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