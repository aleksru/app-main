@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Отчеты
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
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Выберите даты</h3>
            </div>
            <div class="box-body">
                <form id="report" method="post" action="{{ route('docs.report-full') }}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-2">
                            <label>Дата начала</label>
                            <input type="date" class="form-control" name="dateFrom" id="dateFrom">
                        </div>
                        <div class="col-md-2">
                            <label>Дата окончания</label>
                            <input type="date" name="dateTo" class="form-control" id="dateTo">
                        </div>
                        <div class="col-md-2">
                            <label></label>
                            <button type="submit" class="btn btn-primary form-control" id="btn-result">
                                Вывести
                            </button>
                        </div>
                        <div class="col-md-2">
                            <label></label>
                            <button form="report" type="submit" class="btn btn-success form-control">
                                Скачать полный отчет
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {!! $table ?? '' !!}
    </div>
@endsection

@push('scripts')
<script>
    $(function(){
        exchangeData.tableName = "{{ $tableName }}";
        let table = $('#{{$tableName}}').DataTable();
        let inputDateFrom = $('#dateFrom');
        let inputDateTo = $('#dateTo');
        $('#btn-result').click(function (e) {
            e.preventDefault();
            exchangeData.dateFrom = inputDateFrom.val();
            exchangeData.dateTo = inputDateTo.val();
            table.draw();
        });

        $('#{{$tableName}}').on( 'draw.dt', function () {
            table.columns('.sum').every(function () {
                let sum = this.data().reduce(function (a, b) {
                    if(typeof a === "string"){
                       a =  a.replace(/\s+/g, '');
                    }
                    if(typeof b === "string"){
                        b =  b.replace(/\s+/g, '');
                    }

                    let x = parseInt(a) || 0;
                    let y = parseInt(b) || 0;
                    return x + y;
                }, 0);
                $(this.footer()).html(sum.toString().replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 '));
            });
        } );
    });
</script>
@endpush
