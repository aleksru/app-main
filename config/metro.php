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
            'nn'     => 'https://api.hh.ru/metro/66'
        ],
    ],

    /**
     * Алиасы с базой городов
     */
    'aliases' => [
        'Москва' => 'moscow',
        'Питер'  => 'piter',
        'Нижний'  => 'nn',
    ],

    /**
     * Сервис используемый по-умолчанию
     */
    'default' => 'hh',

    /**
     * Не вносить линии в базу
     */
    'ignore_lines_regex' => [
        "/МЦД/"
    ],

    /**
     * Удаляет игнорируемые из базы
     */
    'is_delete_ignores' => false,
];