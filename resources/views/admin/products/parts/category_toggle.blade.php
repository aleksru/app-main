<button class="btn btn-sm btn-success btn-type-toggle" data-route="{{ $route }}" title = "Установить кат. Копия"
        data-id="{{ $id }}" data-type="{{ \App\Enums\ProductCategoryEnums::COPY }}">
    <i class="fa fa-clone"></i>
</button>

<button class="btn btn-sm btn-success btn-type-toggle" data-route="{{ $route }}" title = "Установить кат. Наушники"
        data-id="{{ $id }}" data-type="{{ \App\Enums\ProductCategoryEnums::HEADPHONES }}">
    <i class="fa fa-headphones"></i>
</button>

<button class="btn btn-sm btn-success btn-type-toggle" data-route="{{ $route }}" title = "Установить кат. Восстановленные"
        data-id="{{ $id }}" data-type="{{ \App\Enums\ProductCategoryEnums::RESTORED }}">
    <i class="fa fa-registered"></i>
</button>

<button class="btn btn-sm btn-success btn-type-toggle" data-route="{{ $route }}" title = "Установить кат. Оригинальные"
        data-id="{{ $id }}" data-type="{{ \App\Enums\ProductCategoryEnums::ORIGINAL }}">
    <i class="fa fa-key"></i>
</button>

<button class="btn btn-sm btn-success btn-type-toggle" data-route="{{ $route }}" title = "Установить кат. Подарок"
        data-id="{{ $id }}" data-type="{{ \App\Enums\ProductCategoryEnums::PRESENT }}">
    <i class="fa fa-birthday-cake"></i>
</button>

<button class="btn btn-sm btn-success btn-type-toggle" data-route="{{ $route }}" title = "Установить кат. Приставка"
        data-id="{{ $id }}" data-type="{{ \App\Enums\ProductCategoryEnums::CONSOLE }}">
    <i class="fa fa-gamepad"></i>
</button>

<button class="btn btn-sm btn-success btn-type-toggle" data-route="{{ $route }}" title = "Установить кат. Доставка"
        data-id="{{ $id }}" data-type="{{ \App\Enums\ProductCategoryEnums::DELIVERY }}">
    <i class="fa fa-truck"></i>
</button>