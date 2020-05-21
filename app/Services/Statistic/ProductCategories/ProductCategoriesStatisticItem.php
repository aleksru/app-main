<?php


namespace App\Services\Statistic\ProductCategories;


use App\Enums\ProductCategoryEnums;
use App\Services\Statistic\GeneralStatistic\GeneralItem;

class ProductCategoriesStatisticItem extends GeneralItem
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

    public function setLabel($label)
    {
        $label = ProductCategoryEnums::getCategoriesDescription()[$label] ?? $label;
        parent::setLabel($label);
    }
}