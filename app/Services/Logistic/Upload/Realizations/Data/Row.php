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
    protected $supplierName;
    protected $orderStatus;
    protected $realizationStatus;

    /**
     * Row constructor.
     * @param $orderId
     * @param $operatorName
     * @param $statusText
     * @param $productName
     * @param $productImei
     * @param $supplierPrice
     * @param $productPrice
     * @param $courierPrice
     * @param $courierName
     * @param $supplierName
     * @param $orderStatus
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
        $courierName,
        $supplierName,
        $orderStatus,
        $realizationStatus
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
        $this->supplierName = $supplierName;
        $this->orderStatus = $orderStatus;
        $this->realizationStatus = $realizationStatus;
    }

    /**
     * @return mixed
     */
    public function getRealizationStatus()
    {
        return $this->realizationStatus;
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

    /**
     * @return mixed
     */
    public function getSupplierName()
    {
        return $this->supplierName;
    }

    /**
     * @return mixed
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    public static function builder(): RowBuilder
    {
        return new RowBuilder();
    }
}
