<?php

namespace App\Charts;

use App\Charts\Abstractions\BaseDatePeriodsChart;
use App\Enums\StatusResults;
use App\Repositories\StatisticRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SalesDateChart extends BaseDatePeriodsChart
{
    /**
     * @var StatisticRepository
     */
    protected $statRepository;

    /**
     * SalesDateChart constructor.
     * @param Carbon $dateFrom
     * @param Carbon|null $dateTo
     */
    public function __construct(Carbon $dateFrom, Carbon $dateTo = null)
    {
        parent::__construct($dateFrom, $dateTo);
        $this->statRepository= new StatisticRepository();
        $this->title('Продажи/Отказы ' . $this->dateFrom->format('d.m.Y') . '-' . $this->dateTo->format('d.m.Y'));
    }

    public function generateChart()
    {
        $this->addSuccessSales()
            ->addRefusalSales()
            ->addAllSales();
    }

    public function addSuccessSales()
    {
        $this->dataset('Продажи', 'bar', $this->prepareDataForDataset($this->getSalesForType(StatusResults::SUCCESS)))
            ->options([
                'backgroundColor' => '#1ed305',
                'color' => '#3c8dbc',
            ]);

        return $this;
    }

    public function addRefusalSales()
    {
        $this->dataset('Отказы', 'line', $this->prepareDataForDataset($this->getSalesForType(StatusResults::REFUSAL)))
            ->options([
            'backgroundColor' => '#e40c0c',
            'color' => '#e40c0c',
        ]);

        return $this;
    }

    public function addAllSales()
    {
        $this->dataset('Все', 'line', $this->prepareDataForDataset($this->getSalesForType()));

        return $this;
    }

    protected function getSalesForType(int $type = null) : Collection
    {
        return $this->statRepository->getStatCountSalesByDate($this->dateFrom, $this->dateTo, $type)->mapWithKeys(function ($item) {
            return [$item->created_day => $item->count_sales];
        });
    }
}
