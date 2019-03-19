<?php

namespace App\Http\Controllers\Service\DocumentBuilder\OrderDocs\DataDocs;


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
     * @return string
     */
    public function getTemplate():string;
}