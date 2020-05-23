<?php


namespace App\Services\Statistic\CouriersAcc;


use App\Services\Statistic\GeneralStatistic\GeneralItem;

class CouriersAccStatisticItem extends GeneralItem
{
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
}