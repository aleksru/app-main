<?php

return [
    /**
     * Уникальный код вашей АТС
     */
    'api_key' => env('MANGO_UID'),

    /**
     * Ключ для создания подписи
     */
    'api_salt' => env('MANGO_KEY'),

    /**
     * Адрес API Виртуальной АТС
     */
    'api_url' => env('MANGO_API_URL', 'https://app.mango-office.ru/vpbx/'),

    /**
     * Включение\отключение отправки смс через манго
     */
    'enable_send_sms' => true,
];