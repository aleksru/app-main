<?php

namespace App\Http\Controllers\Service\DocumentBuilder\OrderDocs\FullReport;


use App\Enums\ProductType;
use App\Models\Operator;
use App\Models\OrderStatus;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

abstract class BaseFullReport
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var Carbon
     */
    protected $dateStart;

    /**
     * @var Carbon
     */
    protected $dateEnd;

    /**
     * BaseFullReport constructor.
     * @param Carbon $dateStart
     * @param Carbon $dateEnd
     */
    public function __construct (Carbon $dateStart, Carbon $dateEnd)
    {
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
    }

    /**
     * Рассчет статусов
     *
     * @param Collection $values
     * @param string $typeStatus
     * @return string
     */
    protected function calcStatuses(Collection $values, string $typeStatus) : string
    {
        $typeStatus = Cache::remember($typeStatus, 5, function () use ($typeStatus){
            return OrderStatus::getIdStatusForType($typeStatus);
        });

        $cnt = $values->reject(function ($item) use ($typeStatus) {
            return $item->status_id !== $typeStatus;
        })->count();

        if($cnt === 0) {
            return $cnt . "(0%)";
        }

        $proc = $cnt * 100 / $values->count();
        $proc = round ($proc, 1);

        return $cnt . " ({$proc}%)";
    }

    /**
     * Рассчет продаж
     *
     * @param Collection $collection
     * @return array
     */
    protected function calcSales(Collection $collection) : array
    {
        $cntOrderRealiz = 0;
        $summary = 0;
        $summaryMainProduct = 0;
        $summaryOther = 0;

        $collection->each(function ($value) use(&$cntOrderRealiz, &$summary, &$summaryMainProduct, &$summaryOther) {
            $value->realizations->each(function ($item) use (&$summaryMainProduct, &$summaryOther, &$summary, &$cntOrderRealiz) {
                $summary += $item->price * $item->quantity;
                $cntOrderRealiz++;
                if($item->product->type === ProductType::TYPE_PRODUCT){
                    $summaryMainProduct += $item->price * $item->quantity;
                }else{
                    $summaryOther += $item->price * $item->quantity;
                }
            });
        });

        $data['avg_check'] = $collection->count() > 0 ? round($summary / $collection->count(), 1) : 0;
        $data['main_check'] = round($summary);
        $data['main_product_sum'] = $summaryMainProduct;
        $data['main_other_sum'] = $summaryOther;

        return $data;

    }

    /**
     * @return array
     */
    abstract public function prepareDataSheet() : array;
}