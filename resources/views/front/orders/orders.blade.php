@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Заказы
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
        <div class="alert alert-warning">
          <ul>
              <li>Товары: Количество-Артикул-Название-ЦенаЗаШт</li>
          </ul>
        </div>
        <div class="box-header">
            <a href="{{route('orders.create')}}" target="_blank"><button class="btn btn-sm btn-primary pull-right">
                    <i class="fa fa-plus"></i> Новый заказ
                </button></a>
        </div>
        <div class="col-md-12">
            @include('datatable.datatable',[
                'id' => 'orders-table',
                'route' => $routeDatatable ?? route('orders.datatable'),
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
                    ],
                    'additional_phones' => [
                        'name' => 'Доп телефоны',
                        'width' => '3%',
                        'searchable' => true,
                        'orderable' => 'false'
                    ],
                    'communication_time' => [
                        'name' => 'Время перезвона',
                        'width' => '3%',
                        'searchable' => false,
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
                    'actions' => [
                        'name' => 'Действия',
                        'width' => '2%',
                        'orderable' => 'false'
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

    /**
     *обновление таблицы
     */
    $(function () {
        setInterval( function () {
            $('#orders-table').DataTable().ajax.reload(null, false);
        }, 5000 );
    });

    /**
     * Переход на редактирование заказа по клику по строке
     */
    $('#orders-table').on( 'draw.dt', function () {
        $('.row-link').click(function(){
            window.open($(this).find('a').first().attr('href'), '_blank');
        });
    } );

    /**
     * Стиль футер под хедер
     */
    $(function(){
        $('tfoot').css('display', 'table-header-group');

    });

    /**
     * Индивидуальный поиск поле инпут
     * @type Object
     */
    let individualSearchingColumnsInput = {
        phone: {type: 'text'},
        additional_phones: {type: 'text'},
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
            data: {!! json_encode(App\Store::select('id', 'name')->get()->toArray()) !!}
        },
        status_text: {
            data: {!! json_encode(App\Models\OrderStatus::select('id', 'status as name')->get()->toArray()) !!}
        },
        courier: {
            data: {!! json_encode(App\Models\Courier::select('id', 'name')->get()->toArray()) !!}
        },
        operator: {
            data: {!! json_encode(App\Models\Operator::select('id', 'name')->get()->toArray()) !!}
        }
    };

    /**
     * Добавляем поля для поиска, вешаем события
     */
    $('#orders-table').on( 'init.dt', function () {
        let tableOrders = $('#orders-table').DataTable();
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

        $('tfoot').find('select').css('width', '80%').css('min-height', '32px');
        $('tfoot').find('input').css('width', '100%').css('padding', '3px').css('box-sizing', 'border-box');
    } );

</script>
@endpush

