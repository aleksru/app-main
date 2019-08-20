
<div class="table-responsive">
    <table id="{{ $id }}" class="table table-striped table-datatable" style="width: 100%;">
        <thead>
            <tr>
                @foreach ($columns as $name => $column)
                    <th class="th-{{ $name }}">{{ $column['name'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tfoot>
            <tr>
                @foreach ($columns as $name => $column)
                    <th rowspan="1" colspan="1"></th>
                @endforeach
            </tr>
        </tfoot>
    </table>
</div>

@push('scripts')
<script type="text/javascript">
    $(function () {
        /**
         * Базовые настройки таблицы.
         */
        const table = $('#{{ $id }}').DataTable({
            language: {
                url: '/js/ru.json',
            },
            @if(isset($ordering) && !$ordering)
                ordering: false,
            @endif
            pageLength: {{ $pageLength ?? 50}} ,
            processing: false,
            serverSide: true,
            stateSave: false,
            searchDelay: 500,
            order: [[1, 'desc']],
            scrollX: true,
            scrollCollapse: true,
            select: 'multi',
            ajax: {
                url: '{{ $route }}',
                type: 'get',
                error(err){
                    console.log(err);
                    setTimeout(function () {
                        table.ajax.reload(null, false);
                    }, 5000);
                }
            },
            columns: [
                @foreach ($columns as $name => $column)
                    {
                        data: '{{ $name }}',
                        name: '{{ $name }}',
                        width: '{{ $column['width'] ?? '' }}',
                        @if(isset($column['className']))
                            className: '{{$column['className']}}'
                        @endif
                    },
                @endforeach
            ],
            columnDefs: [
                {
                    'targets': 0,
                    'checkboxes': {
                        'selectRow': true
                    }
                },
                {
                    orderable: false,
                    targets: [
                        @foreach ($columns as $name => $column)
                                @if (!empty($column['orderable']) && $column['orderable'])
                            'th-{{ $name }}',
                        @endif
                        @endforeach
                    ]
                },
                {
                    searchable: false,
                    targets: [
                        @foreach ($columns as $name => $column)
                            @if (empty($column['searchable']))
                                'th-{{ $name }}',
                            @endif
                        @endforeach
                    ]
                }
            ]
        }).search( '' ).columns().search( '' ).draw();
    });
</script>
@endpush
