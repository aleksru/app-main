<div class="table-responsive">
    <table id="{{ $id }}" class="table table-striped table-datatable" style="width: 100%;">
        <thead>
        <tr>
            @foreach ($columns as $name => $column)
                <th class="th-{{ $name }}" title="{{$column['title'] ?? ''}}">{{ $column['name'] }}</th>
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
                stateSave: true,
                searchDelay: 500,
                order: [[0, 'desc']],
                scrollX: true,
                scrollCollapse: true,
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

            /**
             * Дебаунс на поиск
             */
            setTimeout(function () {
                let input = $('#{{ $id }}_filter input[type="search"]');
                input.off().on('keyup cut paste', _.debounce(() => table.search(input.val()).draw(), table.settings()[0].searchDelay));
            }, 500);

            /**
             * Обработчик на тогглы
             */
            $('#{{ $id }}').on('click', '.btn-toggle', function () {
                let route = $(this).data('route');
                let id    = $(this).data('id');

                let toastID = 'toast-toggle-' + id;

                if ($('#' + toastID).length > 0)
                    return false;

                toast.loading('Подождите, идет обработка', {
                    id: toastID,
                });

                axios.post(route, {id})
                    .then((response) => {
                        table.ajax.reload(() => {
                            toast.hide(toastID);
                            toast.success(response.data.message);
                        }, false);
                    })
                    .catch((error) => {
                        toast.hide(toastID);
                        toast.error('Ошибка сервера! Пожалуйста, свяжитесь с администратором.');
                    });

            });

            /**
             * Обработчик на кнопку удаления
             */
            $('#{{ $id }}').on('click', '.btn-delete', function () {
                let id      = $(this).data('id');
                let name    = $(this).data('name');
                let route   = $(this).data('route');

                let toastID = 'toast-delete-' + id;

                if ($('#' + toastID).length > 0)
                    return false;

                toast.confirm('Вы действительно хотите удалить элемент "' + name + '"?', function () {
                    let loading = toast.loading('Идет удаление "' + name + '"');
                    axios.delete(route)
                        .then((response) => {
                            table.ajax.reload(() => {
                                toast.hide(loading);
                                toast.success(response.data.message);
                            }, false);
                        })
                        .catch((error) => {
                            toast.hide(loading);
                            toast.error('Ошибка сервера! Пожалуйста, свяжитесь с администратором.');
                        })
                }, null, { id: toastID });
            });


            /**
             * Обработчик на обновления статуса
             */
            $('#{{ $id }}').on('click', '.btn-status', function () {
                let id      = $(this).data('id');
                let name    = $(this).data('name');
                let route   = $(this).data('route');

                let toastID = 'toast-delete-' + id;

                if ($('#' + toastID).length > 0)
                    return false;

                toast.confirm('Вы действительно хотите закрыть заказ "' + name + '"?', function () {
                    let loading = toast.loading('Изменение статуса "' + name + '"');
                    axios.post(route)
                        .then((response) => {
                            table.ajax.reload(() => {
                                toast.hide(loading);
                                toast.success(response.data.message);
                            }, false);
                        })
                        .catch((error) => {
                            toast.hide(loading);
                            toast.error('Ошибка сервера! Пожалуйста, свяжитесь с администратором.');
                        })
                }, null, { id: toastID });
            });

// вынесено
// если нужно динамически обновлять данные - в шаблон добавить скрипт
//            $(function () {
//                setInterval( function () {
//                    $('#idTable').DataTable().ajax.reload(null, false);
//                }, 5000 );
//            });

            //Добавляем скролл по событию инициализации
            @if( ! isset($disableHeaderScroll) )
            $('#{{ $id }}').on( 'init.dt', function () {
                // Enable THEAD scroll bars
                $('.dataTables_scrollHead').css('overflow', 'auto');

                // Sync THEAD scrolling with TBODY
                $('.dataTables_scrollHead').on('scroll', function () {
                    $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
                });
            });
            @endif
        })
    </script>
@endpush
