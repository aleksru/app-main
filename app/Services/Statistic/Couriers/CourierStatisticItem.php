<?php


namespace App\Services\Statistic\Couriers;


use App\Services\Statistic\GeneralStatistic\GeneralItem;

class CourierStatisticItem extends GeneralItem
{
    public $courierPayment = 0;
    public $sumPriceOpt = 0;
    public $sumPriceSales = 0;

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
    public function getCourierPayment(): int
    {
        return $this->courierPayment;
    }

    /**
     * @param int $courierPayment
     */
    public function setCourierPayment(int $courierPayment)
    {
        $this->courierPayment = $courierPayment;
    }

    /**
     * @return int
     */
    public function getSumPriceOpt(): int
    {
        return $this->sumPriceOpt;
    }

    /**
     * @param int $sumPriceOpt
     */
    public function setSumPriceOpt(int $sumPriceOpt)
    {
        $this->sumPriceOpt = $sumPriceOpt;
    }

    /**
     * @return int
     */
    public function getSumPriceSales(): int
    {
        return $this->sumPriceSales;
    }

    /**
     * @param int $sumPriceSales
     */
    public function setSumPriceSales(int $sumPriceSales)
    {
        $this->sumPriceSales = $sumPriceSales;
    }
}