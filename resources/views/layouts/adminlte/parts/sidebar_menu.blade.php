<ul class="sidebar-menu" data-widget="tree">
    <li class="header">Основное</li>
    <!-- Optionally, you can add icons to the links -->
    @can('index', App\Product::class)
        <li><a href="{{ route('product.index') }}"><i class="fa fa-upload"></i> <span>Загрузка прайса</span></a></li>
    @endcan

    @can('view', App\Order::class)
        <li><a href="{{ route('orders.index') }}"><i class="fa fa-newspaper-o"></i> <span>Заказы</span></a></li>
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
                    </ul>
                </li>
            </ul>
        </li>
    @endif
</ul>