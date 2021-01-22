<?php


namespace App\Services\Statistic\Couriers;

use App\Services\Statistic\GeneralStatistic\GeneralReportTableRender;

class CourierStatisticTableRender extends GeneralReportTableRender
{
    public function getColumns() : array
    {
        $columns = [
            'courierPayment' => [
                'name' => 'ЗП курьера',
                'width' => '5%',
                'className' => 'sum',
            ],
            'sumPriceOpt' => [
                'name' => 'Сумма закупки',
                'width' => '5%',
                'className' => 'sum',
            ],
            'sumPriceSales' => [
                'name' => 'Сумма продажи',
                'width' => '5%',
                'className' => 'sum',
            ],
        ];

        return array_merge(parent::getColumns(), $columns);
    }
}
