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
            'warning'
        ];
    }
}
