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
            'light-blue',
            'green',
            'aqua',
            'red',
            'yellow',
            'gray',
            'navy',
            'teal',
            'purple',
            'orange',
            'maroon',
            'green-active',
            'orange-active',
            'yellow-active'
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

if (! function_exists('reduction_string')) {
    /**
     * @param string $str
     * @param int $wordLength
     * @return string
     */
    function reduction_string(string $str, int $wordLength = 3)
    {
        $words = explode(' ', $str);
        for ($i = 0; $i < count($words); $i++){
            //debug(substr($words[$i], 0, $wordLength));
            if(mb_strlen($words[$i]) > $wordLength){
                $words[$i] = mb_substr($words[$i], 0, $wordLength) . '.';
            }
        }

        return implode(' ', $words);
    }
}