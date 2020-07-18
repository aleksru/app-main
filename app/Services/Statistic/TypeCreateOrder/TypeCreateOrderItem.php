<?php


namespace App\Services\Statistic\TypeCreateOrder;


use App\Services\Statistic\Abstractions\BaseStatisticItem;

class TypeCreateOrderItem extends BaseStatisticItem
{
    public $orderId;
    public $createdAt;
    public $product;
    public $type;

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
     * @param mixed $orderId
     */
    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt->format('d.m.y H:s');
    }

    /**
     * @param mixed $product
     */
    public function setProduct(string $product): void
    {
        $this->product = $product;
    }

    /**
     * @param mixed $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
