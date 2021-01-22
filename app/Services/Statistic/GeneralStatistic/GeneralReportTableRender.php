<?php


namespace App\Services\Statistic\GeneralStatistic;


use App\Services\Statistic\Abstractions\BaseReportTableRender;

class GeneralReportTableRender extends BaseReportTableRender
{
    /**
     * @var array
     */
    protected const COLUMNS = [
        'label' => [
            'name' => '',
            'width' => '5%'
        ],
        'done' => [
            'name' => 'Выполен',
            'width' => '5%',
            'className' => 'sum',
        ],
        'missed' => [
            'name' => 'Отказ',
            'width' => '5%',
            'className' => 'sum',
        ],
        'sumOrders' => [
            'name' => 'Итого',
            'width' => '5%',
            'className' => 'sum',
        ],
        'percentDone' => [
            'name' => '% выполнен',
            'width' => '5%',
        ],
        'percentMissed' => [
            'name' => '% отказ',
            'width' => '5%',
        ],
        'profit' => [
            'name' => 'Прибыль',
            'width' => '5%',
            'className' => 'sum',
        ],
        'avgInvoice' => [
            'name' => 'Средний чек',
            'width' => '5%',
            'className' => 'avg',
        ],
        'avgMainInvoice' => [
            'name' => 'Средний чек(общ)',
            'width' => '5%',
            'className' => 'avg',
        ],
        'avgProfit' => [
            'name' => 'Средняя прибыль',
            'width' => '5%',
            'className' => 'avg',
        ],
        'percentOfTotal' => [
            'name' => '% прибыли от общего объема',
            'width' => '5%',
        ],
    ];

    public function getColumns() : array
    {
        return array_merge(parent::getColumns(), self::COLUMNS);
    }
}
