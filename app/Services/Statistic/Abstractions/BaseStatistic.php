<?php


namespace App\Services\Statistic\Abstractions;

use Carbon\Carbon;

abstract class BaseStatistic
{
    protected $dateFrom;
    protected $dateTo;
    protected $container;

    /**
     * DeliveryTimeStatistic constructor.
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     */
    public function __construct(Carbon $dateFrom, Carbon $dateTo)
    {
        $this->container = new StatisticContainer();
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    protected function getOrCreateFieldOnContainer(BaseStatisticItem $item) : BaseStatisticItem
    {
        $field = $this->container->getField($item->getField());
        if( ! $field ){
            $this->container->addField($item->getField(), $item);
        }

        return $this->container->getField($item->getField());
    }

    public function getDataContainer()
    {
        return $this->container->getContainer();
    }

    public function getContainerOnCollection()
    {
        return collect($this->getDataContainer());
    }

    abstract public function generateAll();

    abstract public static function createTableRender(string $routeIndex, string $routeDatatable,
                                                      string $labelHeader = null, string $name = null) : BaseReportTableRender;

    abstract public static function createDefaultTableRender() : BaseReportTableRender;
}