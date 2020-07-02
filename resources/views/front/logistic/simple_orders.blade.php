@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Таблица заказов Логистика
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
        <div class="col-md-6">
            <courier-print></courier-print>
        </div>
        <div class="col-md-6">
            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">Статус склада</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body" style="display: none;">
                    <div class="box-body">
                        <div class="col-sm-6">
                            <form id="mass_change_stock_status" type="submit" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 control-label">Статус склада</label>

                                            <div class="col-sm-8">
                                                <select class="js-example-status-stock-single form-control" name="stock_status_id">
                                                    <option value="" selected>Не выбран</option>
                                                </select>
                                            </div>
                                        </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button class="btn btn-success">
                                            <i class="fa fa-save"></i> Сохранить
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">

                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="box box-warning">
        <div class="box-body">
            <div class="col-md-12">
                @include('datatable.datatable',[
                    'id' => 'orders-table',
                    'route' => $routeDatatable ?? route('orders.datatable'),
                    'ordering' => true,
                    'columns' => \App\Http\Controllers\LogisticController::COLUMNS_SIMPLE_TABLE
                ])
                <fast-order :table-id="'orders-table'"
                            :stock-statuses='@json(\App\Models\OtherStatus::typeStockStatuses()->get())'
                            :logistic-statuses='@json(\App\Models\OtherStatus::typeLogisticStatuses()->get())'
                            :min-margin="{{config('realization.min_margin_order')}}"
                            :min-margin-product="{{config('realization.min_margin_product')}}">
                </fast-order>
                <fast-changes :table-id="'orders-table'"
                              :stock_statuses='@json(\App\Models\OtherStatus::typeStockStatuses()->get())'>

                </fast-changes>
            </div>
        </div>

        <div id="table_preloader" class="overlay" style="display: none">
            <i class="fa fa-refresh fa-spin" style="top: 5%; font-size: 100px;color: #222d32;"></i>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const individualSearchingColumnsInput = {
            client_phone: {type: 'text'},
            imei: {type: 'text'},
            address: {type: 'text'},
            date_delivery: {type: 'text', className: "datepicker-here" },
            id: {type: 'text'},
        };

        /**
         * Индивидуальный поиск поле селект
         * @type Object
         */
        const individualSearchingColumnsSelect = {
            courier_name: {
                className: 'js-example-couriers-single',
                data: []
            },
            status_stock: {
                className: 'js-example-status-stock-single',
                data: []
            },
            status_logist: {
                className: 'js-example-status-logist-single',
                data: []
            },
        };

        $('#orders-table').on( 'draw.dt', function (e, settings) {
            $('.select-stock-statuses').on('change', function() {
                axios.post(`/orders-logistic-event/${this.dataset.orderId}/update`, {
                    stock_status_id: this.value
                })
                .then((res) => {
                    toast.success('Успешно обновлено!');
                }).catch((err) => {
                    console.log(err);
                    toast.error('Произошла ошибка!');
                });
            });

            $('.select-courier').select2({
                minimumResultsForSearch: Infinity,
                dropdownAutoWidth: true,
                ajax: {
                    url: function (params) {
                        return `/courier/order/${this[0].dataset.orderId}/get`;
                    },

                    processResults: function (data) {
                        let items = [{id: '', text: 'Не выбран'}];
                        for (let courier of data){
                            items.push({id: courier.id, text: courier.name})
                        }
                        return {
                            results: items
                        };
                    }
                }
            });

            $('.select-courier').on('select2:select', function (e) {
                axios.post(`/orders-logistic-event/${this.dataset.orderId}/update`, {
                    courier_id: this.value
                })
                .then((res) => {
                    toast.success('Успешно обновлено!');
                })
                .catch((err) => {
                    console.log(err);
                    toast.error('Произошла ошибка!');
                });
            });
        });

        $(function() {
            let stockStatuses = {!! json_encode(\App\Models\OtherStatus::typeStockStatuses()->select('id', 'name as text')->get()->toArray()) !!};
            $('.js-example-status-stock-single').select2({
                data: stockStatuses,
                allowClear: true,
                placeholder: "Выберите статус...",
            });

            $("#mass_change_stock_status").on("submit", function(e){
                e.preventDefault();
                const formData = new FormData(e.target);

                if(formData.get('stock_status_id') == false){
                    toast.error('Не выбран статус!');
                    return false;
                }
                sendMassChange(formData).then((res) => {
                    toast.success('Успешно обновлено!');
                }).catch((err) => {
                    if(err instanceof ErrorValidateException){
                        toast.error(err.toString());
                    }else{
                        toast.error('Что то пошло не так (');
                    }
                });

            });

            async function sendMassChange(formData) {
                let isAppendForm = false;
                $("input[name='checked_order']").each(function () {
                    if(this.checked){
                        formData.append('order_ids[]', $(this).parent().parent().data("order-id"));
                        isAppendForm = true;
                    }
                });
                if( ! isAppendForm ){
                    throw new ErrorValidateException('Не выбраны заказы!');
                }

                const response = await axios.post('/orders-logistic-mass-change-status/update', formData);

                return response;
            }

            function ErrorValidateException(message) {
                this.message = message;
                this.toString = function() {
                    return this.message
                };
            }
        });

        /**
         *обновление таблицы
         */
        $(function () {

            //инициализация таблицы
            $('#orders-table').on( 'init.dt', function () {
                $('.dataTables_filter').hide();
                rewriteSearchColumns();
                $('.js-example-couriers-single').select2({
                    data: {!! json_encode($couriersSelect) !!},
                    dropdownCssClass: 'fs-12',
                });
                $('.js-example-status-stock-single').select2({
                    data: {!! json_encode($statusesStockSelect) !!},
                    dropdownCssClass: 'fs-12',
                });
                $('.js-example-status-logist-single').select2({
                    data: {!! json_encode($statusesLogisticSelect) !!},
                    dropdownCssClass: 'fs-12',
                });
                let tableOrders = $('#orders-table').DataTable();
                const indDateDelivery = tableOrders.settings().init().columns.findIndex((element, index) => element.name == 'date_delivery');

                $(".datepicker-here").datepicker({
                    range: true,
                    clearButton: true,
                    dateFormat: 'yyyy.mm.dd',
                    autoClose: true,
                    onSelect(formattedDate, date, inst){
                        tableOrders.columns(indDateDelivery).search(formattedDate).draw();//, tableOrders.settings()[0].searchDelay
                        $('#table_preloader').show();
                    },
                });

            });

            //событие перерисовки таблицы
            $('#orders-table').on( 'draw.dt', function (e, settings) {
                let tableOrders = $('#orders-table').DataTable();

                setTimeout(() => {
                    const row = $('.dataTables_scrollHead thead tr:eq(1) th');
                    const sumAllPriceIndex = tableOrders.settings().init().columns.findIndex((element, index) => element.name == 'sum_sales');
                    const sumAllPriceOptIndex = tableOrders.settings().init().columns.findIndex((element, index) => element.name == 'sum_price_opt');
                    const sumAllProfitIndex = tableOrders.settings().init().columns.findIndex((element, index) => element.name == 'sum_profit');
                    $(row[sumAllPriceIndex]).text(divideNumberByPieces(parseInt(settings.json.total_price)));
                    $(row[sumAllPriceOptIndex]).text(divideNumberByPieces(parseInt(settings.json.total_price_opt)));
                    $(row[sumAllProfitIndex]).text(divideNumberByPieces(parseInt(settings.json.total_price - settings.json.total_price_opt)));
                }, 0);

                $('#table_preloader').hide();
            });

            function divideNumberByPieces(x, delimiter) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, delimiter || " ");
            }

            /**
             * Добавляем поля для поиска, вешаем события
             */
            function rewriteSearchColumns() {
                let tableOrders = $('#orders-table').DataTable();
                let columns = tableOrders.settings().init().columns;

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
                            tableOrders.columns(i).search(input.val()).draw();//, tableOrders.settings()[0].searchDelay
                        }, 1000));

                    }

                    if(columnName in individualSearchingColumnsSelect) {
                        const className = individualSearchingColumnsSelect[columnName]['className'];
                        let select = $('<select '+ (className ? `class=${className}` : "") + '><option value="">Не выбран</option></select>');
                        $(this).html(select);
                        for(let key in individualSearchingColumnsSelect[columnName]['data']) {
                            select.append( '<option value="' + individualSearchingColumnsSelect[columnName]['data'][key]['id'] + '">'
                                + individualSearchingColumnsSelect[columnName]['data'][key]['name']  + '</option>' );
                        }
                        select.on('change', async function(e){
                            $('#table_preloader').show();
                            tableOrders.columns(i).search($(this).val()).draw();//, tableOrders.settings()[0].searchDelay;
                        });
                    }
                } );

                $('thead').find('select').css('width', '80%').css('min-height', '32px');
                $('thead').find('input').css('width', '100%').css('padding', '3px').css('box-sizing', 'border-box');
            }
        });

    </script>
@endpush