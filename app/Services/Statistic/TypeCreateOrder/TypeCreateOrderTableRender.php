<?php


namespace App\Services\Statistic\TypeCreateOrder;


use App\Services\Statistic\Abstractions\BaseReportTableRender;

class TypeCreateOrderTableRender extends BaseReportTableRender
{
    public function __construct(string $routeIndex, string $routeDatatable, string $labelHeader = null, string $name = null)
    {
        parent::__construct($routeIndex, $routeDatatable, $labelHeader, $name === null ? 'type-create-order' : $name);
    }

    public function getColumns() : array
    {
        $columns = [
            'orderId' => [
                'name' => '№Заказа',
                'width' => '5%',
            ],
            'createdAt' => [
                'name' => 'Создан',
                'width' => '10%',
            ],
            'product' => [
                'name' => 'Товар',
                'width' => '25%',
            ],
            'type' => [
                'name' => 'Тип',
                'width' => '5%',
            ],
        ];
        return array_merge($columns, parent::getColumns());
    }

    public function renderSingleReport()
    {
        return view('statistic.type_created_orders.single_report', ['DTRender' => $this]);
    }

    public function renderTable()
    {
        return view('statistic.type_created_orders.table', ['render' => $this]);
    }
}
