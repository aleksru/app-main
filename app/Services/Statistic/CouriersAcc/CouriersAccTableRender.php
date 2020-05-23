<?php


namespace App\Services\Statistic\CouriersAcc;


use App\Services\Statistic\GeneralStatistic\GeneralReportTableRender;

class CouriersAccTableRender extends GeneralReportTableRender
{
    public function __construct(string $routeIndex, string $routeDatatable, string $labelHeader = null, string $name = null)
    {
        parent::__construct($routeIndex,  $routeDatatable,  $labelHeader,  ($name === null) ? 'couriers-acc' : $name);
    }
}