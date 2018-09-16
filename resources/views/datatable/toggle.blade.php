{{-- 

@todo нужно сделать сообщения для тоггла; ставим просто код, который на все тоглы вешает обработчик, обработчик шлет запросы аяксом и выводит сообщение ответа, все; 
@todo+ нужно подключить изи тоасты 
@todo+ нужно проверить bootstrap-sass порт admin-lte для читабельности

@todo+ сделать seo модуль
@todo+ сделать модуль для загрузки изображений

--}}
@if ($check)
    <button class="btn btn-xs btn-success btn-toggle" data-route="{{ $route }}" data-id="{{ $id }}" data-status="activated">
        <i class="fa fa-check"></i>
    </button>
@else
    <button class="btn btn-xs btn-warning btn-toggle" data-route="{{ $route }}" data-id="{{ $id }}" data-status="deactivated">
        <i class="fa fa-ban"></i>
    </button>
@endif