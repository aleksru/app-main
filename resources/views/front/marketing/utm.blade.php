@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            UTM
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
        @include('datatable.datatable',[
            'id' => 'utm-table',
            'route' => route('marketing.utm.datatable'),
            'pageLength' => 100,
            'columns' => [
                'id' => [
                    'name' => 'ID',
                    'width' => '1%',
                    'searchable' => true,
                ],
                'utm_source' => [
                    'name' => 'utm_source',
                    'width' => '5%',
                    'searchable' => true,
                    'orderable' => 'false'
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
                'operator' => [
                    'name' => 'Оператор',
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
            ],
        ])
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

    //инициализация таблицы
    $('#utm-table').on( 'init.dt', function () {
        rewriteSearchColumns();
    });

    /**
     * Индивидуальный поиск поле инпут
     * @type Object
     */
    let individualSearchingColumnsInput = {
        phone: {type: 'text'},
        name_customer: {type: 'text'},
        created_at: {type: 'date'},
        id: {type: 'text'},
    };

    /**
     * Индивидуальный поиск поле селект
     * @type Object
     */
    let individualSearchingColumnsSelect = {
        store_text: {
            data: {!! json_encode(App\Store::active()->select('id', 'name')->get()->toArray()) !!}
        },
        status_text: {
            data: {!! json_encode(App\Models\OrderStatus::select('id', 'status as name')->get()->toArray()) !!}
        },
        operator: {
            data: {!! json_encode(App\Models\Operator::select('id', 'name')->get()->toArray()) !!}
        },
        utm_source: {
            data: {!! json_encode(App\Order::selectRaw('utm_source as name')->distinct('utm_source')->whereNotNull('utm_source')->get()) !!}
        },
    };

    /**
     * Добавляем поля для поиска, вешаем события
     */
    function rewriteSearchColumns() {
        let tableOrders = $('#utm-table').DataTable();
        let columns = tableOrders.settings().init().columns;

        $('.dataTables_scrollHead thead tr').clone(true).appendTo( '.dataTables_scrollHead thead' );
        $('.dataTables_scrollHead thead tr:eq(1) th').each( function (i) {
            this.className = this.className.replace(/sorting.*/, 'sorting_disabled');
            let columnName = columns[i].name;
            $(this).html('');
            if(columnName in individualSearchingColumnsInput) {
                let input = $('<input type="' + individualSearchingColumnsInput[columnName]['type'] + '" value="" placeholder="Search...">');
                $(this).html(input);
                input.off().on('keyup cut paste change', _.debounce((e) => {
                    tableOrders.columns(i).search(input.val()).draw(), tableOrders.settings()[0].searchDelay
                }));
            }

            if(columnName in individualSearchingColumnsSelect) {
                let select = $('<select><option value=""></option></select>');
                $(this).html(select);
                for(let key in individualSearchingColumnsSelect[columnName]['data']) {
                    let val = individualSearchingColumnsSelect[columnName]['data'][key]['id'] || individualSearchingColumnsSelect[columnName]['data'][key]['name'];
                    select.append( '<option value="' + val + '">'
                        + individualSearchingColumnsSelect[columnName]['data'][key]['name']  + '</option>' );
                }
                select.on('change', function(){
                    tableOrders.columns(i).search($(this).val()).draw(), tableOrders.settings()[0].searchDelay;
                });
            }
        } );

        $('thead').find('select').css('width', '80%').css('min-height', '32px');
        $('thead').find('input').css('width', '100%').css('padding', '3px').css('box-sizing', 'border-box');
    }

</script>
@endpush
