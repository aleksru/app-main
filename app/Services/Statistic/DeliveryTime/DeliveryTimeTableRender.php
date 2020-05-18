<?php


namespace App\Services\Statistic\DeliveryTime;


use App\Services\Statistic\GeneralStatistic\GeneralReportTableRender;

class DeliveryTimeTableRender extends GeneralReportTableRender
{
    /**
     * DeliveryTimeTableRender constructor.
     * @param string $routeIndex
     * @param string $routeDatatable
     * @param string|null $labelHeader
     * @param string|null $name
     */
    public function __construct(string $routeIndex, string $routeDatatable, string $labelHeader = null, string $name = null)
    {
        parent::__construct($routeIndex,  $routeDatatable,  $labelHeader = null,  ($name === null) ? 'delivery' : $name);
    }

}