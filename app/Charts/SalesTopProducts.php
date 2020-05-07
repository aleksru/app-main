<?php

namespace App\Charts;


use App\Charts\Abstractions\BaseDateChart;
use App\Repositories\StatisticRepository;
use Carbon\Carbon;

class SalesTopProducts extends BaseDateChart
{
    public static $LIMIT_PRODUCTS = 15;

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
        $this->title('Топ-' . self::$LIMIT_PRODUCTS . ' продаж товаров ' .
                        $this->dateFrom->format('d.m.Y') . '-' . $this->dateTo->format('d.m.Y'));
    }

    public function generateChart()
    {
        $results = $this->statRepository->getTopSalesProducts($this->dateFrom, $this->dateTo, self::$LIMIT_PRODUCTS);
        $this->labels($results->pluck('product'));
        $data = $results->pluck('count_sales')->toArray();
        $this->dataset('Продажи', 'doughnut', $data)
            ->options([
                "backgroundColor" => array_slice(get_all_colors_css(), 0, count($data))
            ]);
    }
}