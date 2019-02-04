<ul class="sidebar-menu" data-widget="tree">
    <li class="header">Основное</li>
    <!-- Optionally, you can add icons to the links -->
    @can('index', App\Product::class)
        <li><a href="{{ route('product.index') }}"><i class="fa fa-upload"></i> <span>Загрузка прайса</span></a></li>
    @endcan

    @can('index', App\Product::class)
        <li><a href="{{ route('price-lists.index') }}"><i class="fa fa-credit-card"></i> <span>Прайс-листы</span></a></li>
    @endcan

    @can('view', App\Order::class)
        <li><a href="{{ route('orders.index') }}"><i class="fa fa-newspaper-o"></i> <span>Заказы</span></a></li>
        <li><a href="{{ route('docs.index') }}"><i class="fa fa-print" aria-hidden="true"></i> <span>Принт-форм</span> </a></li>
    @endcan

    @can('view', App\Models\StockUser::class)
        <li><a href="{{ route('stock.index') }}"><i class="fa fa-recycle" aria-hidden="true"></i> <span>Склад</span></a></li>
    @endcan

    @can('view', App\Models\Logist::class)
        <li><a href="{{ route('logistics.index') }}"><i class="fa fa-random" aria-hidden="true"></i> <span>Логистика</span></a></li>
    @endcan

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
                        <li><a href="{{ route('admin.delivery-periods.index') }}"><i class="fa fa-truck" aria-hidden="true"></i> <span>Время доставки</span> </a></li>
                        <li><a href="{{ route('admin.order-statuses.index') }}"><i class="fa fa-tags" aria-hidden="true"></i> <span>Статусы заказа</span> </a></li>
                        <li><a href="{{ route('admin.suppliers.index') }}"><i class="fa fa-shopping-bag" aria-hidden="true"></i> <span>Поставщики</span> </a></li>
                        <li><a href="{{ route('admin.operators.index') }}"><i class="fa fa-headphones" aria-hidden="true"></i> <span>Операторы</span> </a></li>
                        <li><a href="{{ route('admin.couriers.index') }}"><i class="fa fa-street-view" aria-hidden="true"></i> <span>Курьеры</span> </a></li>
                        <li><a href="{{ route('admin.price-types.index') }}"><i class="fa fa-money" aria-hidden="true"></i> <span>Прайс-листы</span> </a></li>
                        <li><a href="{{ route('admin.denial-reasons.index') }}"><i class="fa fa-hand-paper-o" aria-hidden="true"></i> <span>Причины отказа</span> </a></li>
                        <li><a href="{{ route('admin.delivery-types.index') }}"><i class="fa fa-car" aria-hidden="true"></i> <span>Типы доставки</span> </a></li>
                        <li><a href="{{ route('admin.stock.index') }}"><i class="fa fa-recycle" aria-hidden="true"></i> <span>Склад</span> </a></li>
                        <li><a href="{{ route('admin.logists.index') }}"><i class="fa fa-random" aria-hidden="true"></i> <span>Логисты</span> </a></li>
                    </ul>
                </li>
            </ul>
        </li>
    @endif
</ul>