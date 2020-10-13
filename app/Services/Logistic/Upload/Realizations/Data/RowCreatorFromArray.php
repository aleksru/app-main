<?php


namespace App\Services\Logistic\Upload\Realizations\Data;

use App\Exceptions\Realizations\Upload\ValidateRowException;
use App\Services\Logistic\Upload\Realizations\Interfaces\RowCreatorImp;

class RowCreatorFromArray implements RowCreatorImp
{
    /**
     * @var array
     */
    protected $rowArr;

    /**
     * @var RowBuilder
     */
    protected $builder;

    /**
     * RowCreatorFromArray constructor.
     */
    public function __construct()
    {
        $this->builder = new RowBuilder();
    }

    /**
     * @param array $rowArr
     * @throws ValidateRowException
     */
    public function setRowArr(array $rowArr): void
    {
        $this->validateArrRow($rowArr);
        $this->rowArr = $rowArr;
    }

    public function create(): Row
    {
       return $this->builder->setOrderId((int)$this->rowArr['id'])
                       ->setCourierPrice($this->rowArr['courier.price'] === null ? null : (int)$this->rowArr['courier.price'])
                       ->setOperatorName($this->rowArr['operator.name'])
                       ->setProductImei($this->rowArr['product.IMEI'])
                       ->setProductName($this->rowArr['product.name'])
                       ->setStatusText($this->rowArr['status'])
                       ->setSupplierPrice($this->rowArr['supplier.price'] === null ? null : (int)$this->rowArr['supplier.price'])
                       ->setProductPrice($this->rowArr['product.price'] === null ? null : (int)$this->rowArr['product.price'])
                       ->setCourierName($this->rowArr['courier.name'] ?? null)
                       ->setSupplierName($this->rowArr['supplier.name'] ?? null)
                       ->setOrderStatus($this->rowArr['order.status'] ?? null)
                       ->build();
    }

    /**
     * @param array $rowArr
     * @return bool
     * @throws ValidateRowException
     */
    public function validateArrRow(array $rowArr): bool
    {
        $require = ['id', 'product.name', 'product.IMEI', 'product.price'];
        foreach ($require as $item){
            if(! array_key_exists($item, $rowArr) ){
                throw new ValidateRowException('Row cannot be created, validated error! ' . implode(',', $rowArr));
            }
        }

        return true;
    }
}
