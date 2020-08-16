<?php


namespace App\Services\Logistic\Upload\Realizations\Data;


class Row
{
    protected $orderId;
    protected $operatorName;
    protected $statusText;
    protected $productName;
    protected $productImei;
    protected $supplierPrice;
    protected $productPrice;
    protected $courierPrice;
    protected $courierName;

    /**
     * Row constructor.
     * @param int $orderId
     * @param string|null $operatorName
     * @param string|null $statusText
     * @param string $productName
     * @param string|null $productImei
     * @param int|null $supplierPrice
     * @param int|null $productPrice
     * @param int|null $courierPrice
     * @param string|null $courierName
     */
    public function __construct(
         $orderId,
        $operatorName,
        $statusText,
        $productName,
        $productImei,
        $supplierPrice,
        $productPrice,
        $courierPrice,
        $courierName
    )
    {
        $this->orderId = $orderId;
        $this->operatorName = $operatorName;
        $this->statusText = $statusText;
        $this->productName = $productName;
        $this->productImei = $productImei;
        $this->supplierPrice = $supplierPrice;
        $this->productPrice = $productPrice;
        $this->courierPrice = $courierPrice;
        $this->courierName = $courierName;
    }

    /**
     * @return int
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @return string
     */
    public function getOperatorName()
    {
        return $this->operatorName;
    }

    /**
     * @return string
     */
    public function getStatusText()
    {
        return $this->statusText;
    }

    /**
     * @return string
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * @return string|null
     */
    public function getProductImei()
    {
        return $this->productImei;
    }

    /**
     * @return int|null
     */
    public function getSupplierPrice()
    {
        return $this->supplierPrice;
    }

    /**
     * @return int|null
     */
    public function getProductPrice()
    {
        return $this->productPrice;
    }

    /**
     * @return int|null
     */
    public function getCourierPrice()
    {
        return $this->courierPrice;
    }

    /**
     * @return mixed
     */
    public function getCourierName()
    {
        return $this->courierName;
    }

    public static function builder(): RowBuilder
    {
        return new RowBuilder();
    }
}
