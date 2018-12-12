@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Принт-форм
            <small></small>
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

            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Курьер</h3>
                </div>
                <form id="cour" role="form" method="post" class="form-horizontal" action="{{ route('docs.form') }}">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="col-sm-8">
                            <label for="name" class="control-label">Курьер</label>
                            <select class="js-example-couriers-single form-control" name="courier">
                                <option value="" selected>Не выбран</option>
                                <option value="{{ null }}">  </option>
                            </select>
                        </div>
                        <div class="col-sm-8">
                            <label for="name" class="control-label">Дата</label>
                            <input type="date" class="form-control"  value="" name="date_delivery">
                        </div>
                        <div class="col-sm-12">
                            <button form="cour" type="submit" class="btn btn-primary pull-right ">
                                Сформировать
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Ежедневный отчет</h3>
                </div>
                <form id="report" role="report" method="post" class="form-horizontal" action="{{ route('docs.report') }}">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="col-sm-8">
                            <label for="name" class="control-label">Дата</label>
                            <input type="date" class="form-control"  value="" name="date">
                        </div>
                        <div class="col-sm-12">
                            <button form="report" type="submit" class="btn btn-primary pull-right ">
                                Сформировать
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(isset($courier) && $courier)
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Список документов для {{$courier->name}} на {{ $toDate ?? date('d.m.Y') }}</h3>
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
                        <tr>
                            <td></td>
                            <td>Маршрутный лист</td>
                            <td> <a href="{{ route('docs.route-map', ['courier' => $courier->id, 'date' => $toDate]) }}">
                                    <i class="fa fa-print btn btn-info" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><strong>Счета</strong></td>
                            <td>
                            </td>
                        </tr>
                        @foreach ($courier->orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>Счет№ {{ $order->id }} от {{ $order->created_at }}</td>
                                <td> <a href="{{ route('docs.market-check', $order->id) }}">
                                        <i class="fa fa-print btn btn-info" aria-hidden="true"></i>
                                    </a>
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
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    @if (session()->has('success'))
        toast.success('{{ session()->get('success') }}')
    @endif
    @if (session()->has('error'))
        toast.error('{{ session()->get('error') }}')
    @endif

    let couriers = {!!   json_encode(\App\Models\Courier::select('id', 'name as text')->get()->toArray()) !!}

    $(function() {
        $('.js-example-couriers-single').select2({
            data: couriers,
            allowClear: true,
            placeholder: "Выберите курьера...",
        });
    });
</script>
@endpush