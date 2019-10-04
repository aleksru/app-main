@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            UTM Статусы заказа
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
    <div class="col-md-12">
        <div class="box box-default">
            <div class="box-header">

            </div>
            <div class="box-header with-border">

            </div>
            <div class="box-body">
                <form id="report" method="get" action="{{ route('reports.utm_status') }}">
                    <div class="row">
                        <div class="col-md-2">
                            <label>Дата начала</label>
                            <input type="date" class="form-control" name="dateFrom" id="dateFrom" required>
                        </div>
                        <div class="col-md-2">
                            <label>Дата окончания</label>
                            <input type="date" name="dateTo" class="form-control" id="dateTo">
                        </div>
                        <div class="col-sm-4">
                            <label for="name" class="control-label">Источники</label>
                            <select class="js-example-stores-multiple form-control" name="store_ids[]" multiple="multiple">
                                <option value="{{ null }}"></option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label></label>
                            <button type="submit" class="btn btn-primary form-control" id="btn-result">
                                <p>Вывести</p>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="box">
            <!-- /.box-header -->
            <div class="col-md-12">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>UTM</th>
                                @foreach($statuses as $status)
                                    <th class="bg-{{$status->color}}">{{$status->status}}</th>
                                @endforeach
                                <th>Итого</th>
                            </tr>
                            @foreach($orders as $utm => $order)
                                <tr>
                                    <td>{{$utm}}</td>

                                    @for($k=0; $k < $statuses->count(); $k++)
                                        <td class="bg-{{$statuses[$k]->color}}">
                                            {{ $order->first(function ($value, $key) use(&$statuses, &$k) {
                                                return $value->status_id == $statuses[$k]->id ;
                                            })->cnt ?? 0}}
                                        </td>
                                    @endfor
                                    <td>{{$order->sum('cnt')}}</td>
                                </tr>

                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let stores = {!!   json_encode(\App\Store::active()->select('id', 'name as text')->get()->toArray()) !!}
    $(function() {
        $('.js-example-stores-multiple').select2({
            data: stores,
            allowClear: true,
            placeholder: "Выберите магазин...",
        });
    });
</script>

@endpush
