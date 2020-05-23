<?php


namespace App\Services\Statistic\Suppliers;


use App\Services\Statistic\GeneralStatistic\GeneralReportTableRender;

class SuppliersStatisticTableRender extends GeneralReportTableRender
{
    public function __construct(string $routeIndex, string $routeDatatable, string $labelHeader = null, string $name = null)
    {
        parent::__construct($routeIndex,  $routeDatatable,  $labelHeader,  ($name === null) ? 'suppliers' : $name);
    }

}