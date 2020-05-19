<?php


namespace App\Services\Statistic\Dates;


use App\Services\Statistic\GeneralStatistic\GeneralReportTableRender;

class DatesStatisticTableRender extends GeneralReportTableRender
{
    /**
     * DatesStatisticTableRender constructor.
     * @param string $routeIndex
     * @param string $routeDatatable
     * @param string|null $labelHeader
     * @param string|null $name
     */
    public function __construct(string $routeIndex, string $routeDatatable, string $labelHeader = null, string $name = null)
    {
        parent::__construct($routeIndex,  $routeDatatable,  $labelHeader,  ($name === null) ? 'dates' : $name);
    }

}