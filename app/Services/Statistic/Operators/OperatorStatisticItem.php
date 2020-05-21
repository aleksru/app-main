<?php


namespace App\Services\Statistic\Operators;


use App\Services\Statistic\GeneralStatistic\GeneralItem;

class OperatorStatisticItem extends GeneralItem
{
    public $totalSumSalesAccessory = 0;
    public $countSalesAirPods = 0;
    public $totalSumSalesAirPods = 0;

    public function __construct($field)
    {
        parent::__construct($field);
        $this->setLabel($field);
    }

    /**
     * @override
     * @param mixed $field
     */
    public function setField($field)
    {
        parent::setField($field);
        $this->setLabel($field);
    }

    /**
     * @return int
     */
    public function getTotalSumSalesAccessory(): int
    {
        return $this->totalSumSalesAccessory;
    }

    /**
     * @param int $totalSumSalesAccessory
     */
    public function setTotalSumSalesAccessory(int $totalSumSalesAccessory)
    {
        $this->totalSumSalesAccessory = $totalSumSalesAccessory;
    }

    /**
     * @return int
     */
    public function getCountSalesAirPods(): int
    {
        return $this->countSalesAirPods;
    }

    /**
     * @param int $countSalesAirPods
     */
    public function setCountSalesAirPods(int $countSalesAirPods)
    {
        $this->countSalesAirPods = $countSalesAirPods;
    }

    /**
     * @return int
     */
    public function getTotalSumSalesAirPods(): int
    {
        return $this->totalSumSalesAirPods;
    }

    /**
     * @param int $totalSumSalesAirPods
     */
    public function setTotalSumSalesAirPods(int $totalSumSalesAirPods)
    {
        $this->totalSumSalesAirPods = $totalSumSalesAirPods;
    }


}