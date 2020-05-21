<?php


namespace App\Services\Statistic\Operators;


use App\Services\Statistic\GeneralStatistic\GeneralReportTableRender;

class OperatorsStatisticTableRender extends GeneralReportTableRender
{
    public function __construct(string $routeIndex, string $routeDatatable, string $labelHeader = null, string $name = null)
    {
        parent::__construct($routeIndex,  $routeDatatable,  $labelHeader,  ($name === null) ? 'operators' : $name);
    }

    public function getColumns(): array
    {
        $columns = [
            'totalSumSalesAccessory' => [
                'name' => 'Сумма продаж аксессуаров',
                'width' => '5%',
            ],
            'countSalesAirPods' => [
                'name' => 'Кол-во проданных аирподсов',
                'width' => '5%',
            ],
            'totalSumSalesAirPods' => [
                'name' => 'Сумма продаж аирподсов',
                'width' => '5%',
            ],
        ];

        return array_merge(parent::getColumns(), $columns);
    }
}