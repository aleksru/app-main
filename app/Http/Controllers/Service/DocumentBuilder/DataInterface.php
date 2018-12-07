<?php

namespace App\Http\Controllers\Service\DocumentBuilder;


interface DataInterface
{
    /**
     * Подготавливает данные к передаче в шаблон
     * @return $this
     */
    public function prepareData();

    /**
     * Получение даннх
     * @return array
     */
    public function getData();

    /**
     * Получение имени файла
     * @return string
     */
    public function getFileName();
}