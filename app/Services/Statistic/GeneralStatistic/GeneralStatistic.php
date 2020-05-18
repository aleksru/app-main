<?php


namespace App\Services\Statistic\GeneralStatistic;


use App\Services\Statistic\Abstractions\IContainer;
use App\Services\Statistic\Abstractions\IGeneralStatisticGenerate;

class GeneralStatistic
{
    protected $container;
    protected $statisticGenerate;

    /**
     * GeneralStatistic constructor.
     * @param IContainer $container
     * @param IGeneralStatisticGenerate $statisticGenerate
     */
    public function __construct(IContainer $container, IGeneralStatisticGenerate $statisticGenerate)
    {
        $this->container = $container;
        $this->statisticGenerate = $statisticGenerate;
    }

    public function genAll()
    {
        $this->genDone();
        $this->genMissed();
        $this->genProfit();
        $this->genAvgInvoice();
        $this->genAvgProfit();
        $this->genPercentOfFullSales();
    }

    public function genDone()
    {
        $items = $this->statisticGenerate->genDone();
        foreach ($items as $item){
            if( ! ($item instanceof GeneralItem) ){
                throw new \InvalidArgumentException(get_class($item) . ' not instanceof GeneralItem!');
            }
            $field = $this->getOrCreateFieldOnContainer($item);
            $field->setDone($item->getDone());
        }
    }

    public function genMissed()
    {
        $items = $this->statisticGenerate->genMissed();
        foreach ($items as $item){
            if( ! ($item instanceof GeneralItem) ){
                throw new \InvalidArgumentException(get_class($item) . ' not instanceof GeneralItem!');
            }
            $field = $this->getOrCreateFieldOnContainer($item);
            $field->setMissed($item->getMissed());
        }
    }

    public function genProfit()
    {
        $items = $this->statisticGenerate->genProfit();
        foreach ($items as $item){
            if( ! ($item instanceof GeneralItem) ){
                throw new \InvalidArgumentException(get_class($item) . 'Item not instanceof GeneralItem!');
            }
            $field = $this->getOrCreateFieldOnContainer($item);
            $field->setProfit($item->getProfit());
        }
    }

    public function genAvgInvoice()
    {
        $items = $this->statisticGenerate->genAvgInvoice();
        foreach ($items as $item){
            if( ! ($item instanceof GeneralItem) ){
                throw new \InvalidArgumentException(get_class($item) . ' not instanceof GeneralItem!');
            }
            $field = $this->getOrCreateFieldOnContainer($item);
            $field->setAvgInvoice($item->getAvgInvoice());
        }
    }

    public function genAvgProfit()
    {
        $items = $this->statisticGenerate->genAvgProfit();
        foreach ($items as $item){
            if( ! ($item instanceof GeneralItem) ){
                throw new \InvalidArgumentException(get_class($item) . ' not instanceof GeneralItem!');
            }
            $field = $this->getOrCreateFieldOnContainer($item);
            $field->setAvgProfit($item->getAvgProfit());
        }
    }

    public function genPercentOfFullSales()
    {
        $totalSum = $this->statisticGenerate->getSumSales();
        foreach ($this->container->getContainer() as $item){
            if( ! ($item instanceof GeneralItem) ){
                throw new \InvalidArgumentException(get_class($item) . ' not instanceof GeneralItem!');
            }
            $item->setPercentOfTotal($item->getDone() / $totalSum * 100);
        }
    }

    protected function getOrCreateFieldOnContainer(GeneralItem $item) : GeneralItem
    {
        $field = $this->container->getField($item->getField());
        if( ! $field ){
            $this->container->addField($item->getField(), $item);
        }

        return $this->container->getField($item->getField());
    }

}