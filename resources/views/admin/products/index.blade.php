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
    <div class="col-md-8">
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

    <div class="box box-warning">

        <div class="box-header">
            <a href="{{route('admin.products.create')}}"><button class="btn btn-sm btn-primary pull-right">
                    <i class="fa fa-plus"></i> Создать
                </button></a>
        </div>

        <div class="box-body">
            @include('datatable.datatable',[
                'id' => 'products-table',
                'route' => route('admin.products.datatable'),
                'columns' => [
                    'id' => [
                        'name' => 'ID',
                        'width' => '1%',
                        'searchable' => false,
                    ],
                    'article' => [
                        'name' => 'article',
                        'width' => '1%',
                        'searchable' => true,
                    ],
                    'product_name' => [
                        'name' => 'Товар',
                        'width' => '10%',
                        'searchable' => true,
                    ],
                    'type' => [
                        'name' => 'Тип',
                        'width' => '5%',
                        'searchable' => false,
                    ],
                    'category' => [
                        'name' => 'Категория',
                        'width' => '5%',
                        'searchable' => false,
                    ],
                    'actions' => [
                        'name' => 'Действия',
                        'width' => '10%',
                        'orderable' => 'false'
                    ],

                ],
            ])
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#products-table').on('click', '.btn-type-toggle', function() {
            let route = $(this).data('route');
            let id    = $(this).data('id');
            let type    = $(this).data('type');
            let toastID = 'toast-toggle-' + id;

            if ($('#' + toastID).length > 0)
                return false;

            toast.loading('Подождите, идет обработка', {
                id: toastID,
            });

            axios.post(route, {type: type, data: 'test'})
            .then((response) => {
                $('#products-table').DataTable().ajax.reload(() => {
                    toast.hide(toastID);
                    toast.success(response.data.message);
                }, false);
            }).catch((error) => {
                console.log(error);
                toast.hide(toastID);
                toast.error('Ошибка сервера! Пожалуйста, свяжитесь с администратором.');
            });
        });
    </script>
@endpush