<?php

if (! function_exists('get_class_colors')) {
    /**
     * Получение класса цветов bootstrap
     *
     * @return array
     */
    function get_class_colors()
    {
        return [
            'default',
            'primary',
            'success',
            'info',
            'danger',
            'warning',
            'gray',
            'navy',
            'teal',
            'purple',
            'orange',
            'maroon'
        ];
    }
}

if (! function_exists('get_rus_month')) {
    /**
     * Получение месяца рус
     *
     * @return array
     */
    function get_rus_month(int $month)
    {
        $monthes = [
            'января',
            'февраля',
            'марта',
            'апреля',
            'мая',
            'июня',
            'июля',
            'августа',
            'сентября',
            'октября',
            'ноября',
            'декабря',
        ];

        return $monthes[$month];
    }
}
