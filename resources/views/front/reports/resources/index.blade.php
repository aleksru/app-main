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
                            <input type="text" class="form-control" name="date_from" id="dateFrom" value=""/>
                        </div>
                        <div class="col-md-2">
                            <label>Дата окончания</label>
                            <input type="text" name="date_to" class="form-control" id="dateTo" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-2">
                            <label></label>
                            <button type="submit" class="btn btn-primary form-control" id="btn-result">
                                <p>Вывести</p>
                                <img src="/images/preloader3.gif" style="display: none">
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
        $('input[name="date_from"]').datepicker({
            timepicker: true,
            maxDate: new Date(),
            clearButton: true,
        });
        $('input[name="date_to"]').datepicker({
            timepicker: true,
            maxDate: new Date(),
            clearButton: true,
        });
        exchangeData.tableName = "{{ $tableName }}";
        let table = $('#{{$tableName}}').DataTable();
        let inputDateFrom = $('#dateFrom');
        let inputDateTo = $('#dateTo');
        $('#btn-result').click(function (e) {
            e.preventDefault();
            exchangeData.dateFrom = inputDateFrom.val();
            exchangeData.dateTo = inputDateTo.val();
            togglePreloader();
            table.draw();
        });
        $.fn.dataTable.ext.errMode = 'throw';

        new $.fn.dataTable.FixedColumns( table, {
            leftColumns: 1,
        } );

        function togglePreloader() {
            $('#btn-result p').toggle();
            $('#btn-result img').toggle();
            $('#btn-result').prop('disabled', ! $('#btn-result').prop('disabled'));
        }

        $('#{{$tableName}}').on( 'xhr.dt', function () {
            togglePreloader();
        } );

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
        });

        $('#{{$tableName}}').on( 'init.dt column-sizing.dt', function () {
            let top = $('.dataTables_scrollHead').height() - $('.dataTables_scrollHeadInner').height() - 6;
            $('.DTFC_LeftBodyWrapper .DTFC_LeftBodyLiner').css('top', top);
        });
    });

</script>
@endpush
