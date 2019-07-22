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
        <div class="col-md-2">
            <button id="btn_activate_call_center" type="button" class="btn btn-block btn-default" disabled>Коллцентр</button>
        </div>
        <div class="col-md-2">
            <button id="btn_activated_complaint" type="button" class="btn btn-block btn-default">Рекламации</button>
        </div>
    </div>
    <div class="col-md-12">
        @include('datatable.datatable',[
            'id' => 'calls-table',
            'route' => route('calls.datatable'),
            'columns' => [
                'client_id' => [
                    'name' => 'Клиент',
                    'width' => '5%',
                ],
                'from_number' => [
                    'name' => 'Телефон',
                    'width' => '10%',
                ],
                'store_id' => [
                    'name' => 'Магазин',
                    'width' => '10%',
                    'searchable' => true,
                ],
                'fca' => [
                    'name' => 'Время',
                    'width' => '10%',
                ],

            ],
        ])
    </div>
@endsection

@push('scripts')
<script>
    $(function () {
        /**
         *обновление таблицы
         */
        setInterval( function () {
            $('#calls-table').DataTable().ajax.reload(null, false);
        }, 5000 );
    });

    /**
     * Стиль футер под хедер
     */
    $(function(){
        $('tfoot').css('display', 'table-header-group');

    });

    /**
     * Столбцы поиска
     */
    let individualSearchingColumnsInput = {
        fca: {type: 'date'},
    };

    let individualSearchingColumnsSelect = {
        store_id: {
            data: {!! json_encode(App\Store::select('id', 'name')->get()->toArray()) !!}
        },
    };

    /**
     * Добавляем поля для поиска, вешаем события
     */
    $('#calls-table').on( 'init.dt', function () {
        let tableOrders = $('#calls-table').DataTable();
        let columns = tableOrders.settings().init().columns;

        tableOrders.columns().every(function(){
            let column = this;
            let columnName = columns[this.index()].name;

            if(columnName in individualSearchingColumnsInput) {
                let input = $('<input type="' + individualSearchingColumnsInput[columnName]['type'] + '" value="" placeholder="Search...">').appendTo( $(column.footer()).empty() );
                input.off().on('keyup cut paste change', _.debounce(() =>  column.search(input.val()).draw(), tableOrders.settings()[0].searchDelay));
            }

            if(columnName in individualSearchingColumnsSelect) {
                let select = $('<select><option value=""></option></select>').appendTo( $(column.footer()).empty() );

                for(let key in individualSearchingColumnsSelect[columnName]['data']) {
                    select.append( '<option value="' + individualSearchingColumnsSelect[columnName]['data'][key]['id'] + '">'
                        + individualSearchingColumnsSelect[columnName]['data'][key]['name']  + '</option>' );
                }
                select.on('change', function(){
                    column.search($(this).val()).draw(), tableOrders.settings()[0].searchDelay;
                });
            }
        });


        $('#btn_activate_call_center').click(function () {
            $('#btn_activated_complaint').attr("disabled", false);
            this.disabled = ! this.disabled;
            exchangeData.isComplaint = false;
            tableOrders.draw();
        });

        $('#btn_activated_complaint').click(function () {
            $('#btn_activate_call_center').attr("disabled", false);
            this.disabled = ! this.disabled;
            exchangeData.isComplaint = true;
            tableOrders.draw();
        });

        $('tfoot').find('select').css('width', '80%').css('min-height', '32px');
        $('tfoot').find('input').css('width', '100%').css('padding', '3px').css('box-sizing', 'border-box');
    } );
</script>
@endpush