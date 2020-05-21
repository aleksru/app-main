<?php


namespace App\Services\Statistic\Stores;


use App\Services\Statistic\GeneralStatistic\GeneralReportTableRender;

class StoresStatisticTableRender extends GeneralReportTableRender
{
    public function __construct(string $routeIndex, string $routeDatatable, string $labelHeader = null, string $name = null)
    {
        parent::__construct($routeIndex,  $routeDatatable,  $labelHeader,  ($name === null) ? 'stores' : $name);
    }
}