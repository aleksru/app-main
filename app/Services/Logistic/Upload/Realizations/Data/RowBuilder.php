<?php


namespace App\Services\Logistic\Upload\Realizations\Data;


class RowBuilder
{
    protected $orderId;
    protected $operatorName;
    protected $statusText;
    protected $productName;
    protected $productImei = null;
    protected $supplierPrice = null;
    protected $productPrice = null;
    protected $courierPrice = null;
    protected $courierName = null;
    protected $supplierName = null;

    /**
     * @param mixed $orderId
     */
    public function setOrderId(int $orderId): self
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * @param mixed $operatorName
     */
    public function setOperatorName($operatorName): self
    {
        $this->operatorName = $operatorName;
        return $this;
    }

    /**
     * @param mixed $statusText
     */
    public function setStatusText($statusText): self
    {
        $this->statusText = $statusText;
        return $this;
    }

    /**
     * @param mixed $productName
     */
    public function setProductName($productName): self
    {
        $this->productName = $productName;
        return $this;
    }

    /**
     * @param mixed $productImei
     */
    public function setProductImei($productImei): self
    {
        $this->productImei = $productImei;
        return $this;
    }

    /**
     * @param mixed $supplierPrice
     */
    public function setSupplierPrice($supplierPrice): self
    {
        $this->supplierPrice = $supplierPrice;
        return $this;
    }

    /**
     * @param mixed $productPrice
     */
    public function setProductPrice($productPrice): self
    {
        $this->productPrice = $productPrice;
        return $this;
    }

    /**
     * @param mixed $courierPrice
     */
    public function setCourierPrice($courierPrice): self
    {
        $this->courierPrice = $courierPrice;
        return $this;
    }

    /**
     * @param $courierName
     * @return $this
     */
    public function setCourierName($courierName): self
    {
        $this->courierName = $courierName;
        return $this;
    }

    /**
     * @param null $supplierName
     */
    public function setSupplierName($supplierName): self
    {
        $this->supplierName = $supplierName;
        return $this;
    }



    public function reset()
    {
        $this->orderId = null;
        $this->operatorName = null;
        $this->statusText = null;
        $this->productName = null;
        $this->productImei = null;
        $this->supplierPrice = null;
        $this->productPrice = null;
        $this->courierPrice = null;
        $this->courierName = null;
        $this->supplierName = null;
    }

    public function build(): Row
    {
        $row =  new Row(
            $this->orderId,
            $this->operatorName,
            $this->statusText,
            $this->productName,
            $this->productImei,
            $this->supplierPrice,
            $this->productPrice,
            $this->courierPrice,
            $this->courierName,
            $this->supplierName
        );
        $this->reset();

        return $row;
    }
}
