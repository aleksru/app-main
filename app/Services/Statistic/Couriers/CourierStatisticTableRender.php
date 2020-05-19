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
            ],
            'sumPriceOpt' => [
                'name' => 'Сумма закупки',
                'width' => '5%',
            ],
            'sumPriceSales' => [
                'name' => 'Сумма продажи',
                'width' => '5%',
            ],
        ];

        return array_merge(parent::getColumns(), $columns);
    }
}