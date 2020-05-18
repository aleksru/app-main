<?php


namespace App\Services\Statistic\GeneralStatistic;


use App\Services\Statistic\Abstractions\BaseStatisticItem;

class GeneralItem extends BaseStatisticItem
{
    public $done = 0;
    public $missed = 0;
    public $sumOrders = 0;
    public $percentDone = 0;
    public $percentMissed = 0;
    public $profit = 0;
    public $avgInvoice = 0;
    public $avgMainInvoice = 0;
    public $avgProfit = 0;
    public $percentOfTotal = 0;

    /**
     * @return int
     */
    public function getAvgMainInvoice()
    {
        return $this->avgMainInvoice;
    }

    /**
     * @param int $avgMainInvoice
     */
    public function setAvgMainInvoice($avgMainInvoice)
    {
        $this->avgMainInvoice = round((float)$avgMainInvoice, 2);
    }

    /**
     * @return mixed
     */
    public function getDone()
    {
        return $this->done;
    }

    private function calcComputedFieldsOrders()
    {
        $this->setSumOrders($this->getDone() + $this->getMissed());
        $this->setPercentDone($this->getDone() / $this->getSumOrders() * 100);
        $this->setPercentMissed($this->getMissed() / $this->getSumOrders() * 100);
    }

    /**
     * @param mixed $done
     */
    public function setDone($done)
    {
        $this->done = $done;
        $this->calcComputedFieldsOrders();
    }

    /**
     * @return mixed
     */
    public function getMissed()
    {
        return $this->missed;
    }

    /**
     * @param mixed $missed
     */
    public function setMissed($missed)
    {
        $this->missed = $missed;
        $this->calcComputedFieldsOrders();
    }

    /**
     * @return mixed
     */
    public function getSumOrders()
    {
        return $this->sumOrders;
    }

    /**
     * @param mixed $sumOrders
     */
    public function setSumOrders($sumOrders)
    {
        $this->sumOrders = $sumOrders;
    }

    /**
     * @return mixed
     */
    public function getPercentDone()
    {
        return $this->percentDone;
    }

    /**
     * @param mixed $percentDone
     */
    public function setPercentDone(float $percentDone)
    {
        $this->percentDone = round($percentDone, 2);
    }

    /**
     * @return mixed
     */
    public function getPercentMissed()
    {
        return $this->percentMissed;
    }

    /**
     * @param mixed $percentMissed
     */
    public function setPercentMissed(float $percentMissed)
    {
        $this->percentMissed = round($percentMissed, 2);
    }

    /**
     * @return mixed
     */
    public function getProfit()
    {
        return $this->profit;
    }

    /**
     * @param mixed $profit
     */
    public function setProfit($profit)
    {
        $this->profit = (float)$profit;
    }

    /**
     * @return mixed
     */
    public function getAvgInvoice()
    {
        return $this->avgInvoice;
    }

    /**
     * @param mixed $avgInvoice
     */
    public function setAvgInvoice($avgInvoice)
    {
        $this->avgInvoice = round((float)$avgInvoice, 2);
    }

    /**
     * @return mixed
     */
    public function getAvgProfit()
    {
        return $this->avgProfit;
    }

    /**
     * @param mixed $avgProfit
     */
    public function setAvgProfit($avgProfit)
    {
        $this->avgProfit = round((float)$avgProfit, 2);
    }

    /**
     * @return mixed
     */
    public function getPercentOfTotal()
    {
        return $this->percentOfTotal;
    }

    /**
     * @param mixed $percentOfTotal
     */
    public function setPercentOfTotal($percentOfTotal)
    {
        $this->percentOfTotal = round((float)$percentOfTotal, 2);
    }


}