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


if (! function_exists('get_string_corp_info')) {
    /**
     * @return string
     */
    function get_string_corp_info()
    {
//  "name" => "Название организации"
//  "inn" => "ИНН"
//  "kpp" => "КПП"
//  "ogrn" => "ОГРН"
//  "address" => "Адрес организации"
//  "gendir" => "Генеральный директор"
        $data = setting('corporate');
        if( ! $data ){
            return '';
        }
        $result = '';
        foreach ($data as $key => $value){
            if(!empty($value)){
                $result .= \App\Enums\CorporateInfoEnums::getDescriptionForField($key) . " " . $value . ", ";
            }
        }

        return $result;
    }
}

if (! function_exists('get_string_delivery_corp_info')) {
    /**
     * @return string
     */
    function get_string_delivery_corp_info()
    {
//  "name" => "Название организации"
//  "inn" => "ИНН"
//  "kpp" => "КПП"
//  "ogrn" => "ОГРН"
//  "address" => "Адрес организации"
//  "gendir" => "Генеральный директор"
        $data = setting('delivery');
        if( ! $data ){
            return '';
        }
        $result = '';
        foreach ($data as $key => $value){
            if(!empty($value)){
                $result .= \App\Enums\DeliveryInfoEnums::getDescriptionForField($key) . " " . $value . ", ";
            }
        }

        return $result;
    }
}
if (! function_exists('get_all_colors_css')) {
    /**
     * @return string
     */
    function get_all_colors_css()
    {
        return [
            "AliceBlue",
            "AntiqueWhite",
            "Aqua",
            "Aquamarine",
            "Azure",
            "Beige",
            "Bisque",
            "Black",
            "BlanchedAlmond",
            "Blue",
            "BlueViolet",
            "Brown",
            "BurlyWood",
            "CadetBlue",
            "Chartreuse",
            "Chocolate",
            "Coral",
            "CornflowerBlue",
            "Cornsilk",
            "Crimson",
            "Cyan",
            "DarkBlue",
            "DarkCyan",
            "DarkGoldenRod",
            "DarkGray",
            "DarkGrey",
            "DarkGreen",
            "DarkKhaki",
            "DarkMagenta",
            "DarkOliveGreen",
            "DarkOrange",
            "DarkOrchid",
            "DarkRed",
            "DarkSalmon",
            "DarkSeaGreen",
            "DarkSlateBlue",
            "DarkSlateGray",
            "DarkSlateGrey",
            "DarkTurquoise",
            "DarkViolet",
            "DeepPink",
            "DeepSkyBlue",
            "DimGray",
            "DimGrey",
            "DodgerBlue",
            "FireBrick",
            "FloralWhite",
            "ForestGreen",
            "Fuchsia",
            "Gainsboro",
            "GhostWhite",
            "Gold",
            "GoldenRod",
            "Gray",
            "Grey",
            "Green",
            "GreenYellow",
            "HoneyDew",
            "HotPink",
            "IndianRed",
            "Indigo",
            "Ivory",
            "Khaki",
            "Lavender",
            "LavenderBlush",
            "LawnGreen",
            "LemonChiffon",
            "LightBlue",
            "LightCoral",
            "LightCyan",
            "LightGoldenRodYellow",
            "LightGray",
            "LightGrey",
            "LightGreen",
            "LightPink",
            "LightSalmon",
            "LightSeaGreen",
            "LightSkyBlue",
            "LightSlateGray",
            "LightSlateGrey",
            "LightSteelBlue",
            "LightYellow",
            "Lime",
            "LimeGreen",
            "Linen",
            "Magenta",
            "Maroon",
            "MediumAquaMarine",
            "MediumBlue",
            "MediumOrchid",
            "MediumPurple",
            "MediumSeaGreen",
            "MediumSlateBlue",
            "MediumSpringGreen",
            "MediumTurquoise",
            "MediumVioletRed",
            "MidnightBlue",
            "MintCream",
            "MistyRose",
            "Moccasin",
            "NavajoWhite",
            "Navy",
            "OldLace",
            "Olive",
            "OliveDrab",
            "Orange",
            "OrangeRed",
            "Orchid",
            "PaleGoldenRod",
            "PaleGreen",
            "PaleTurquoise",
            "PaleVioletRed",
            "PapayaWhip",
            "PeachPuff",
            "Peru",
            "Pink",
            "Plum",
            "PowderBlue",
            "Purple",
            "RebeccaPurple",
            "Red",
            "RosyBrown",
            "RoyalBlue",
            "SaddleBrown",
            "Salmon",
            "SandyBrown",
            "SeaGreen",
            "SeaShell",
            "Sienna",
            "Silver",
            "SkyBlue",
            "SlateBlue",
            "SlateGray",
            "SlateGrey",
            "Snow",
            "SpringGreen",
            "SteelBlue",
            "Tan",
            "Teal",
            "Thistle",
            "Tomato",
            "Turquoise",
            "Violet",
            "Wheat",
            "White",
            "WhiteSmoke",
            "Yellow",
            "YellowGreen",
        ];
    }
}