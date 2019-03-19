<?php

namespace App\Http\Controllers\Service\DocumentBuilder\OrderDocs\DataDocs;


class FullReport implements DataInterface
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Внесение данных из заказа в массив
     * @return $this
     */
    public function prepareData()
    {

    }

    /**
     * Получение данных
     * @return array
     */
    public function getData()
    {

    }

    /**
     * Имя файла
     * @return string
     */
    public function getFileName():string
    {
        return 'Отчет от '.(date("d.m.Y")).'.xlsx';
    }

    /**
     * @return string
     */
    public function getTemplate():string
    {
        return storage_path('app' . DIRECTORY_SEPARATOR . 'exel_templates' . DIRECTORY_SEPARATOR . 'full_report.xlsx') ;
    }

    protected function genSheet1()
    {
        $sheet = [
            'operators' => [],
            'countOrders' => [],
            'avgInvoice' => [],
            'sumMain' => [],
            'countCalls' => [],
        ];

        $this->data->each(function ($value, $key) use (&$sheet) {
            $sheet['operators'][] = $value->operator->name ?? 'Not found';
            $sheet['countOrders'][] = $value->operator->name ?? 'Not found';
        });
    }
}