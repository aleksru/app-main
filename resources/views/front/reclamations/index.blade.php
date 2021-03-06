@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Рекламации
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
        <div class="box box-danger">
            <div class="box-body">
                <div class="col-md-12">
                    @include('datatable.datatable',[
                        'id' => 'orders-table',
                        'route' => route('reclamations.datatable'),
                        'pageLength' => 100,
                        'columns' => [
                            'id' => [
                                'name' => 'ID',
                                'width' => '1%',
                                'searchable' => true,
                            ],
                            'status_text' => [
                                'name' => 'Статус',
                                'width' => '2%',
                                'searchable' => true,
                                'orderable' => 'false'
                            ],
                            'store_text' => [
                                'name' => 'Магазин',
                                'width' => '3%',
                                'searchable' => true,
                                'orderable' => 'false'
                            ],
                            'name_customer' => [
                                'name' => 'Покупатель',
                                'width' => '3%',
                                'searchable' => true,
                                'orderable' => 'false'
                            ],
                            'phone' => [
                                'name' => 'Телефон',
                                'width' => '3%',
                                'searchable' => true,
                                'orderable' => 'false'
                            ],
                            'additional_phones' => [
                                'name' => 'Доп телефоны',
                                'width' => '3%',
                                'searchable' => false,
                                'orderable' => 'false'
                            ],
                            'communication_time' => [
                                'name' => 'Время перезвона',
                                'width' => '3%',
                                'searchable' => true,
                                'orderable' => 'false'
                            ],
                            'operator' => [
                                'name' => 'Оператор',
                                'width' => '3%',
                                'searchable' => true,
                                'orderable' => 'false'

                            ],
                            'courier' => [
                                'name' => 'Курьер',
                                'width' => '3%',
                                'searchable' => true,
                                'orderable' => 'false'

                            ],
                            'products' => [
                                'name' => 'Товары',
                                'width' => '20%',
                                'searchable' => false,
                                'orderable' => 'false'
                            ],
                            'comment' => [
                                'name' => 'Комментарий',
                                'width' => '5%',
                                'searchable' => false,
                                'orderable' => 'false'
                            ],
                            'created_at' => [
                                'name' => 'Дата создания',
                                'width' => '5%',
                                'orderable' => 'false',
                                'searchable' => true,
                            ],
                            'city' => [
                                'name' => 'Город',
                                'width' => '2%',
                                'orderable' => 'false',
                                'searchable' => true,
                            ],
                            'actions' => [
                                'name' => 'Действия',
                                'width' => '2%',
                                'orderable' => 'false'
                            ],

                        ],
                    ])
                </div>
            </div>
            <!-- /.box-body -->
            <!-- Loading (remove the following to stop the loading)-->
            <div id="table_preloader" class="overlay" style="display: none">
                <i class="fa fa-refresh fa-spin" style="top: 5%; font-size: 100px;color: #222d32;"></i>
            </div>
            <!-- end loading -->
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

    let lockUpdate = false;
    //инициализация таблицы
    $('#orders-table').on( 'init.dt', function () {
        rewriteSearchColumns();
        setInterval(function () {
            if(lockUpdate === false) {
                $('#orders-table').DataTable().ajax.reload(null, false);
                lockUpdate = true;
            }
        }, 5000);

        let tableOrders = $('#orders-table').DataTable();
        const indCreAt = tableOrders.settings().init().columns.findIndex((element, index) => element.name == 'created_at');

        $(".datepicker-here").datepicker({
            range: true,
            clearButton: true,
            dateFormat: 'yyyy.mm.dd',
            onSelect(formattedDate, date, inst){
                tableOrders.columns(indCreAt).search(formattedDate).draw();//, tableOrders.settings()[0].searchDelay
                $('#table_preloader').show();
            },
        });

    });

    //событие перерисовки таблицы
    $('#orders-table').on( 'draw.dt', function () {
        //Переход на редактирование заказа по клику по строке
        $('.row-link').click(function(){
            window.open($(this).find('a').first().attr('href'), '_blank');
        });
        lockUpdate = false;
        $('#table_preloader').hide();
    });


    /**
     * Индивидуальный поиск поле инпут
     * @type Object
     */
    let individualSearchingColumnsInput = {
        phone: {type: 'text'},
        name_customer: {type: 'text'},
        created_at: {type: 'text', className: "datepicker-here" },
        id: {type: 'text'},
        communication_time: {type: 'date'},
    };

    /**
     * Индивидуальный поиск поле селект
     * @type Object
     */
    let individualSearchingColumnsSelect = {
        store_text: {
            data: {!! json_encode(App\Store::active()->select('id', 'name')->get()->toArray()) !!}
        },
        courier: {
            data: {!! json_encode(App\Models\Courier::select('id', 'name')->get()->toArray()) !!}
        },
        operator: {
            data: {!! json_encode(App\Models\Operator::select('id', 'name')->isActive()->get()->toArray()) !!}
        },
        city: {
            data: {!! json_encode(App\Models\City::select('id', 'name')->get()->toArray()) !!}
        }
    };

    /**
     * Добавляем поля для поиска, вешаем события
     */
    function rewriteSearchColumns() {
        let tableOrders = $('#orders-table').DataTable();
        let columns = tableOrders.settings().init().columns;
            /*При отключении скролла */
//        $('#orders-table thead tr').clone(true).appendTo( '#orders-table thead' );
//        $('#orders-table thead tr:eq(1) th').each( function (i)

        $('.dataTables_scrollHead thead tr').clone(true).appendTo( '.dataTables_scrollHead thead' );
        $('.dataTables_scrollHead thead tr:eq(1) th').each( function (i) {
            this.className = this.className.replace(/sorting.*/, 'sorting_disabled');

            let columnName = columns[i].name;
            $(this).html('');
            if(columnName in individualSearchingColumnsInput) {
                let input = $('<input type="' + individualSearchingColumnsInput[columnName]['type'] + '" value="" placeholder="Search..."' +
                                ` class="${individualSearchingColumnsInput[columnName]['className']}">`);
                $(this).html(input);
                input.off().on('keyup cut paste change', _.debounce(async (e) => {
                    $('#table_preloader').show();
                    lockUpdate = true;
                    tableOrders.columns(i).search(input.val()).draw();//, tableOrders.settings()[0].searchDelay
                }, 1000));

            }

            if(columnName in individualSearchingColumnsSelect) {
                const isMulti = individualSearchingColumnsSelect[columnName]['multi'] === true;
                let arrMultiNames = [];
                let select = $('<select><option value="-"></option></select>');
                if(isMulti){
                    select.addClass(`js-${columnName}-basic-multiple`);
                    select.attr('multiple', "multiple");
                    arrMultiNames.push(columnName);
                }
                $(this).html(select);
                for(let key in individualSearchingColumnsSelect[columnName]['data']) {
                    select.append( '<option value="' + individualSearchingColumnsSelect[columnName]['data'][key]['id'] + '">'
                        + individualSearchingColumnsSelect[columnName]['data'][key]['name']  + '</option>' );
                }
                select.on('change', async function(){
                    $('#table_preloader').show();
                    lockUpdate = true;
                    tableOrders.columns(i).search($(this).val()).draw();//, tableOrders.settings()[0].searchDelay;
                });
                for(let name of arrMultiNames){
                    $(`.js-${name}-basic-multiple`).select2({
                        allowClear: true,
                        placeholder: "",
                    });
                }
            }
        } );

        $('thead').find('select').css('width', '80%').css('min-height', '32px');
        $('thead').find('input').css('width', '100%').css('padding', '3px').css('box-sizing', 'border-box');
    }
</script>
@endpush
@push('css')
    <style>
        .select2-selection.select2-selection--multiple .select2-selection__choice{
            font-size: 80%
        }
    </style>
@endpush
