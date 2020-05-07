<?php

namespace App\Charts\Abstractions;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

abstract class BaseDatePeriodsChart extends BaseDateChart
{
    /**
     * @var array
     */
    protected $dates = [];

    /**
     * BaseDatePeriodsChart constructor.
     * @param Carbon $dateFrom
     * @param Carbon|null $dateTo
     */
    public function __construct(Carbon $dateFrom, Carbon $dateTo = null)
    {
        parent::__construct($dateFrom, $dateTo);
        $this->generatePeriods();
    }

    /**
     * Generate periods for $dateFrom to $dateTo
     * @return void
     */
    protected function generatePeriods()
    {
        $periods = CarbonPeriod::create($this->dateFrom, $this->dateTo);
        foreach ($periods as $period){
            $this->dates[$period->format('Y-m-d')] = 0;
        }
        $this->labels(array_keys($this->dates));
    }

    /**
     * @param Collection $data
     * @return array
     */
    protected function prepareDataForDataset(Collection $data) : array
    {
        return array_values(array_merge($this->dates, $data->toArray()));
    }
}