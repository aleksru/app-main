<ul class="sidebar-menu" data-widget="tree">
    <li class="header">Основное</li>
    <!-- Optionally, you can add icons to the links -->
    @can('index', App\Product::class)
        <li><a href="{{ route('product.index') }}"><i class="fa fa-upload"></i> <span>Загрузка прайса</span></a></li>
    @endcan

    @can('view', App\ClientCall::class)
        <li><a href="{{ route('calls.index') }}"><i class="fa fa-volume-control-phone"></i> <span>Пропущенные звонки</span></a></li>
    @endcan

    @can('index', App\Product::class)
        <li><a href="{{ route('price-lists.index') }}"><i class="fa fa-credit-card"></i> <span>Прайс-листы</span></a></li>
    @endcan

    @can('view', App\Order::class)
        <li><a href="{{ route('orders.index') }}"><i class="fa fa-newspaper-o"></i> <span>Заказы</span></a></li>
        <li><a href="{{ route('docs.index') }}"><i class="fa fa-print" aria-hidden="true"></i> <span>Принт-форм</span> </a></li>
        <li class="treeview">
            <a href="#"><i class="fa fa-book"></i> <span>Работа с заказом</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{ route('statuses.orders') }}"><i class="fa fa-tags" aria-hidden="true"></i> <span>Смена статусов</span> </a></li>
                <li><a href="{{ route('sms.distribution.index') }}"><i class="fa fa-tags" aria-hidden="true"></i> <span>СМС рассылка</span> </a></li>
            </ul>
        </li>
    @endcan

    @can('view', App\Order::class)
        <li class="treeview">
            <a href="#"><i class="fa fa-tty" aria-hidden="true"></i><span>Звонки</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{ route('ringing.queue') }}"><i class="fa fa-tags" aria-hidden="true"></i> <span>Очередь</span> </a></li>
                {{--<li><a href="{{ route('sms.distribution.index') }}"><i class="fa fa-tags" aria-hidden="true"></i> <span>СМС рассылка</span> </a></li>--}}
            </ul>
        </li>
    @endcan

    @can('view', App\Order::class)
        <li class="treeview">
            <a href="#"><i class="fa fa-book"></i> <span>Отчеты</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{ route('reports.operators') }}"><i class="fa fa-tags" aria-hidden="true"></i> <span>Операторы</span> </a></li>
                <li><a href="{{ route('reports.days') }}"><i class="fa fa-tags" aria-hidden="true"></i> <span>По дням</span> </a></li>
                <li><a href="{{ route('reports.products') }}"><i class="fa fa-tags" aria-hidden="true"></i> <span>Товары</span> </a></li>
                <li><a href="{{ route('reports.resources') }}"><i class="fa fa-tags" aria-hidden="true"></i> <span>Источники</span> </a></li>
                <li><a href="{{ route('reports.operators.created') }}"><i class="fa fa-tags" aria-hidden="true"></i> <span>Созданные заказы</span> </a></li>
                <li><a href="{{ route('reports.operators.orders') }}"><i class="fa fa-tags" aria-hidden="true"></i> <span>Заказы Операторы</span> </a></li>
                <li><a href="{{ route('reports.missed_calls') }}"><i class="fa fa-tags" aria-hidden="true"></i> <span>Пропущенные звонки</span> </a></li>
                <li><a href="{{ route('reports.operators.evening') }}"><i class="fa fa-tags" aria-hidden="true"></i> <span>Вечерний отчет</span> </a></li>
                <li><a href="{{ route('reports.operators.calls') }}"><i class="fa fa-tags" aria-hidden="true"></i> <span>Звонки</span> </a></li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-book"></i> <span>Маркетинг</span>
                        <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('reports.utmReport') }}"><i class="fa fa-tags" aria-hidden="true"></i> <span>UTM</span> </a></li>
                        <li><a href="{{ route('reports.utm_status') }}"><i class="fa fa-tags" aria-hidden="true"></i> <span>UTM статусы заказа</span> </a></li>
                    </ul>
                </li>
            </ul>
        </li>
    @endcan

    @can('view', App\Order::class)
        <li class="treeview">
            <a href="#"><i class="fa fa-magnet" aria-hidden="true"></i><span>Маркетинг</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{ route('marketing.utm') }}"><i class="fa fa-tags" aria-hidden="true"></i> <span>UTM метки</span> </a></li>
            </ul>
        </li>
    @endcan

    @can('view', App\Models\StockUser::class)
        <li><a href="{{ route('stock.index') }}"><i class="fa fa-recycle" aria-hidden="true"></i> <span>Склад</span></a></li>
    @endcan

    @can('view', App\Models\Logist::class)
        <li class="treeview">
            <a href="#"><i class="fa fa-book"></i> <span>Логистика</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{ route('logistics.index') }}"><i class="fa fa-random" aria-hidden="true"></i> <span>Общая таблица</span></a></li>
                <li><a href="{{ route('logistics.simple.orders') }}"><i class="fa fa-random" aria-hidden="true"></i> <span>Реализация</span></a></li>
                <li><a href="{{ route('logistics.deliveries') }}"><i class="fa fa-truck" aria-hidden="true"></i> <span>Периоды доставки</span></a></li>
            </ul>
        </li>
    @endcan
    <li><a href="{{ route('chats') }}"><i class="fa fa-comments" aria-hidden="true"></i><span>Чаты</span></a></li>
    <li><a href="{{ route('logs.version') }}"><i class="fa fa-cogs" aria-hidden="true"></i> <span>О системе</span></a></li>
    @if (Auth::user()->is_admin)
        <li class="treeview">
            <a href="#"><i class="fa fa-link"></i> <span>Админка</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{ route('admin.users.index') }}"><i class="fa fa-users" aria-hidden="true"></i> <span>Пользователи</span> </a></li>
                <li><a href="{{ route('admin.logs.index') }}"><i class="fa fa-file-code-o" aria-hidden="true"></i> <span>Логи</span> </a></li>

                <li class="treeview">
                    <a href="#"><i class="fa fa-book"></i> <span>Справочники</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('admin.stores.index') }}"><i class="fa fa-shopping-basket" aria-hidden="true"></i> <span>Магазины</span> </a></li>
                        <li><a href="{{ route('admin.corporate-info.index') }}"><i class="fa fa-briefcase" aria-hidden="true"></i> <span>Юр лицо</span> </a></li>
                        <li><a href="{{ route('admin.products.index') }}"><i class="fa fa-archive" aria-hidden="true"></i> <span>Товары</span> </a></li>
                        <li><a href="{{ route('admin.delivery-periods.index') }}"><i class="fa fa-truck" aria-hidden="true"></i> <span>Время доставки</span> </a></li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-book"></i> <span>Статусы заказа</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ route('admin.order-statuses.index') }}"><i class="fa fa-tags" aria-hidden="true"></i> <span>Статусы операторов</span> </a></li>
                                <li><a href="{{ route('admin.other-statuses.index') }}"><i class="fa fa-tags" aria-hidden="true"></i> <span>Прочие статусы</span> </a></li>
                                <li><a href="{{ route('admin.denial-reasons.index') }}"><i class="fa fa-hand-paper-o" aria-hidden="true"></i> <span>Причины отказа</span> </a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('admin.suppliers.index') }}"><i class="fa fa-shopping-bag" aria-hidden="true"></i> <span>Поставщики</span> </a></li>
                        <li><a href="{{ route('admin.operators.index') }}"><i class="fa fa-headphones" aria-hidden="true"></i> <span>Операторы</span> </a></li>
                        <li><a href="{{ route('admin.couriers.index') }}"><i class="fa fa-street-view" aria-hidden="true"></i> <span>Курьеры</span> </a></li>
                        <li><a href="{{ route('admin.price-types.index') }}"><i class="fa fa-money" aria-hidden="true"></i> <span>Прайс-листы</span> </a></li>
                        <li><a href="{{ route('admin.delivery-types.index') }}"><i class="fa fa-car" aria-hidden="true"></i> <span>Типы доставки</span> </a></li>
                        <li><a href="{{ route('admin.stock.index') }}"><i class="fa fa-recycle" aria-hidden="true"></i> <span>Склад</span> </a></li>
                        <li><a href="{{ route('admin.logists.index') }}"><i class="fa fa-random" aria-hidden="true"></i> <span>Логисты</span> </a></li>
                        <li><a href="{{ route('admin.cities.index') }}"><i class="fa fa-map-o" aria-hidden="true"></i> <span>Города</span> </a></li>
                        <li><a href="{{ route('admin.chats.index') }}"><i class="fa fa-comments" aria-hidden="true"></i><span>Чаты</span></a></li>
                    </ul>
                </li>
            </ul>
        </li>
    @endif
</ul>