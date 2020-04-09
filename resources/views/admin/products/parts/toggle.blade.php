<button class="btn btn-sm btn-success btn-type-toggle" data-route="{{ $route }}" title = "Установить тип - Товар"
        data-id="{{ $id }}" data-type="{{ \App\Enums\ProductType::TYPE_PRODUCT }}">
    <i class="fa fa-mobile"></i>
</button>

<button class="btn btn-sm btn-info btn-type-toggle" data-route="{{ $route }}" title = "Установить тип - Аксессуар"
        data-id="{{ $id }}" data-type="{{ \App\Enums\ProductType::TYPE_ACCESSORY}}">
    <i class="fa fa-info"></i>
</button>

<button class="btn btn-sm btn-warning btn-type-toggle" data-route="{{ $route }}" title = "Установить тип - Услуга"
        data-id="{{ $id }}" data-type="{{ \App\Enums\ProductType::TYPE_SERVICE}}">
    <i class="fa fa-handshake-o"></i>
</button>