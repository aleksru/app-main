<?php


namespace App\Charts;

use App\Charts\Abstractions\BaseDateChart;
use App\Enums\ProductCategoryEnums;
use App\Repositories\StatisticRepository;
use Carbon\Carbon;

class SalesCategoryDateChart extends BaseDateChart
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
        $this->title('Продажи по категориям ' . $this->dateFrom->format('d.m.Y') . '-' . $this->dateTo->format('d.m.Y'));
    }

    public function generateChart()
    {
        $categories = ProductCategoryEnums::getConstants();
        unset($categories[ProductCategoryEnums::DELIVERY]);
        $results = $this->statRepository->getSalesCategoriesByDate($this->dateFrom, $this->dateTo, $categories);
        $this->labels($this->prepareCategoryDescription($results->pluck('product_category')->toArray()));
        $this->dataset('Продажи', 'pie', $results->pluck('count_sales')->toArray())
            ->options([
                "backgroundColor" => [
                    "rgb(255, 99, 132)",
                    "rgb(75, 192, 192)",
                    "rgb(0,255,0)",
                    "rgb(255, 205, 86)",
                    "rgb(201, 203, 207)",
                    "rgb(54, 162, 235)",
                    "rgb(138,43,226)",
                    "rgb(210,105,30)"
                ]
            ]);
    }

    protected function prepareCategoryDescription(array $results) : array
    {
        $res = [];
        $descriptions = ProductCategoryEnums::getCategoriesDescription();
        foreach ($results as $result){
            $res[] = $descriptions[$result];
        }

        return $res;
    }
}