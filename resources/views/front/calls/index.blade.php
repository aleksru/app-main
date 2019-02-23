@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Пропущенные звонки
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>
    </section>
@endsection
@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="box-header">

    </div>
    <div class="col-md-12">
        @include('datatable.datatable',[
            'id' => 'calls-table',
            'route' => route('calls.datatable'),
            'columns' => [
                'id' => [
                    'name' => 'ID',
                    'width' => '1%',
                    'searchable' => true,
                ],
                'clientName' => [
                    'name' => 'Клиент',
                    'width' => '1%',
                ],
                'from_number' => [
                    'name' => 'Телефон',
                    'width' => '2%',
                ],
                'storeName' => [
                    'name' => 'Магазин',
                    'width' => '2%',
                ],
                'created_at' => [
                    'name' => 'Время',
                    'width' => '2%',
                ],

            ],
        ])
    </div>
@endsection

@push('scripts')
<script>

    /**
     *обновление таблицы
     */
//    $(function () {
//        setInterval( function () {
//            $('#calls-table').DataTable().ajax.reload(null, false);
//        }, 3000 );
//    });
</script>
@endpush