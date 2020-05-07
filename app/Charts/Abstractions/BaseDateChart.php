<?php


namespace App\Charts\Abstractions;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Carbon\Carbon;

abstract class BaseDateChart extends Chart implements IGenerateChart
{
    /**
     * @var Carbon
     */
    protected $dateFrom;

    /**
     * @var Carbon
     */
    protected $dateTo;

    /**
     * SalesCategoryDateChart constructor.
     * @param Carbon $dateFrom
     * @param Carbon|null $dateTo
     */
    public function __construct(Carbon $dateFrom, Carbon $dateTo = null)
    {
        parent::__construct();
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        if($dateTo === null){
            $this->dateTo = clone $dateFrom;
            $this->dateTo->addDay();
        }
    }
}