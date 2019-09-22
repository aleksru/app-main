<?php

return [
    /**
     * Источники
     */
    'sources' => [
        'hh' => '\App\Services\Metro\Sources\HH\Metro',
    ],

    /**
     * Ссылки источников на города
     */
    'links' => [
        'hh' => [
            'moscow' => 'https://api.hh.ru/metro/1',
            'piter'  => 'https://api.hh.ru/metro/2',
        ],
    ],

    /**
     * Алиасы с базой городов
     */
    'aliases' => [
        'Москва' => 'moscow',
        'Питер'  => 'piter',
    ],

    /**
     * Сервис используемый по-умолчанию
     */
    'default' => 'hh',
];