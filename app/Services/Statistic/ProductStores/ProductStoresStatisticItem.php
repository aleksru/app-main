<?php


namespace App\Services\Statistic\ProductStores;


use App\Services\Statistic\GeneralStatistic\GeneralItem;

class ProductStoresStatisticItem extends GeneralItem
{
    public $store;

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
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $fieldNew = explode('#', $label);
        parent::setLabel($fieldNew[0] ?? $label);
        $this->setStore($fieldNew[1] ?? $label);
    }

    /**
     * @return mixed
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * @param mixed $store
     */
    public function setStore($store)
    {
        $this->store = $store;
    }


}
