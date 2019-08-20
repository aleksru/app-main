@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Массовая отправка смс
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
    <div class="box box-warning">
        <div class="box-header">

        </div>
        <div class="box-body">
            <form id="sms-form" role="form" method="post"
                    class="form-horizontal" action="{{route('sms.distribution.send')}}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">СМС</label>

                    <div class="col-sm-6">
                        <textarea rows="4" class="form-control" name="text" placeholder="Текст сообщения"></textarea>
                    </div>

                    <button form="sms-form" type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Отправить
                    </button>
                </div>
            </form>
        </div>
    </div>
    <form id="frm-table" action="#">
        <div class="col-md-12">
            @include('front.sms.parts.datatable',[
                'id' => 'orders-table',
                'route' => $routeDatatable ?? route('orders.datatable'),
                'pageLength' => 100,
                'columns' => [
                    'client_id' => [
                        'name' => '-',
                        'width' => '3%',
                        'searchable' => false,
                        'orderable' => 'false'

                    ],
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
    </form>
@endsection
@push('scripts')
<script>
    @if (session()->has('success'))
        toast.success('{{ session()->get('success') }}')
    @endif
    @if (session()->has('error'))
        toast.error('{{ session()->get('error') }}')
    @endif

    $('#sms-form').on('submit', async function(e){
        e.preventDefault();
        let rows_selected = $('#orders-table').DataTable().column(0).checkboxes.selected();
        let clientIds = [];
        let text = $("#sms-form textarea[name='text']").val();

        $.each(rows_selected, function(index, rowId){
            clientIds.push(rowId);
        });

        if(clientIds.length > 0 && text !== '') {
            sendDistributionSms({clientIds, text})
                .then((res) => {
                    toast.success(res.data.message);
                }).catch((err) => {
                    console.log(err);
                    toast.error('Усс что то пошло не так ((');
            });
        }else{
            toast.error('Не выбраны получатели или пустой текст');
        }
    });

    async function sendDistributionSms(data) {
        let res =  axios.post("{{route('sms.distribution.send')}}", data);

        return res;
    }

    //инициализация таблицы
    $('#orders-table').on( 'init.dt', function () {
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
        courier: {
            data: {!! json_encode(App\Models\Courier::select('id', 'name')->get()->toArray()) !!}
        },
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
                    select.append( '<option value="' + individualSearchingColumnsSelect[columnName]['data'][key]['id'] + '">'
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