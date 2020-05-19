<?php


namespace App\Services\Statistic\GeneralStatistic;


use App\Services\Statistic\Abstractions\BaseStatistic;
use App\Services\Statistic\Abstractions\GeneralStatisticRepository;
use App\Services\Statistic\Abstractions\IContainer;
use App\Services\Statistic\Abstractions\IGeneralStaticItem;
use App\Services\Statistic\Abstractions\IGeneralStatisticRepository;

class GeneralStatistic
{
    protected $container;
    protected $repository;
    protected $generalStatisticItem;

    /**
     * GeneralStatistic constructor.
     * @param IContainer $container
     * @param IGeneralStatisticRepository $repository
     * @param IGeneralStaticItem $generalStaticItem
     */
    public function __construct(IContainer $container, IGeneralStatisticRepository $repository,
                                IGeneralStaticItem $generalStaticItem)
    {
        $this->container = $container;
        $this->repository = $repository;
        $this->generalStatisticItem = $generalStaticItem;
    }

    public function genAll()
    {
        $this->genDone();
        $this->genMissed();
        $this->genProfit();
        $this->genAvgInvoice();
        $this->genAvgProfit();
        $this->genAvgAllInvoices();
        $this->genPercentOfTotalSales();
    }

    public function genDone()
    {
        $items = $this->repository->getDoneCount();
        foreach ($items as $item) {
            $field = $this->getOrCreateFieldOnContainer($item->{$this->repository->getFieldName()});
            $field->setDone($item->{GeneralStatisticDBContract::DONE_COUNT});
        }
    }

    public function genMissed()
    {
        $items = $this->repository->getMissedCount();
        foreach ($items as $item) {
            $field = $this->getOrCreateFieldOnContainer($item->{$this->repository->getFieldName()});
            $field->setMissed($item->{GeneralStatisticDBContract::MISSED_COUNT});
        }
    }

    public function genProfit()
    {
        $items = $this->repository->getProfitByDates();
        foreach ($items as $item) {
            $field = $this->getOrCreateFieldOnContainer($item->{$this->repository->getFieldName()});
            $field->setProfit($item->{GeneralStatisticDBContract::PROFIT});
        }
    }

    public function genAvgInvoice()
    {
        $items = $this->repository->getAvgInvoice();
        foreach ($items as $item) {
            $field = $this->getOrCreateFieldOnContainer($item->{$this->repository->getFieldName()});
            $field->setAvgInvoice($item->{GeneralStatisticDBContract::AVG_INVOICE});
        }
    }

    public function genAvgProfit()
    {
        $items = $this->repository->getAvgProfit();
        foreach ($items as $item) {
            $field = $this->getOrCreateFieldOnContainer($item->{$this->repository->getFieldName()});
            $field->setAvgProfit($item->{GeneralStatisticDBContract::AVG_PROFIT});
        }
    }

    public function genAvgAllInvoices()
    {
        $items = $this->repository->getAvgAllInvoices();
        foreach ($items as $item) {
            $field = $this->getOrCreateFieldOnContainer($item->{$this->repository->getFieldName()});
            $field->setAvgMainInvoice($item->{GeneralStatisticDBContract::AVG_ALL_INVOICES});
        }
    }

    public function genPercentOfTotalSales()
    {
        $totalSum = $this->calcTotalSumSales();
        foreach ($this->container->getContainer() as $item) {
            if (!($item instanceof GeneralItem)) {
                throw new \InvalidArgumentException(get_class($item) . ' not instanceof GeneralItem!');
            }
            $percent = $totalSum > 0 ? $item->getProfit() / $totalSum * 100 : 0;
            $item->setPercentOfTotal($percent);
        }
    }

    protected function calcTotalSumSales()
    {
        return array_reduce($this->container->getContainer(), function ($prev, GeneralItem $curr){
                return $prev += $curr->getProfit();
        }, 0);
    }

    protected function getOrCreateFieldOnContainer($key) : GeneralItem
    {
        $field = $this->container->getField($key);
        if( ! $field ){
            $this->container->addField($key, $this->generalStatisticItem->createGeneralItem($key));
        }

        return $this->container->getField($key);
    }

}