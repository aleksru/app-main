<?php


namespace App\Services\Statistic\PickUp;


use App\Services\Statistic\GeneralStatistic\GeneralReportTableRender;

class PickUpStatisticTableRender extends GeneralReportTableRender
{
    public function __construct(string $routeIndex, string $routeDatatable, string $labelHeader = null, string $name = null)
    {
        parent::__construct($routeIndex,  $routeDatatable,  $labelHeader,  ($name === null) ? 'pickup' : $name);
    }
}