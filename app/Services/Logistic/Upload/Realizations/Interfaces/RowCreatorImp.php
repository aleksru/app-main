<?php


namespace App\Services\Logistic\Upload\Realizations\Interfaces;


use App\Exceptions\Realizations\Upload\ValidateRowException;
use App\Services\Logistic\Upload\Realizations\Data\Row;

interface RowCreatorImp
{
    public function setRowArr(array $rowArr): void;

    public function create(): Row;

    /**
     * @param array $rowArr
     * @return bool
     * @throws ValidateRowException
     */
    public function validateArrRow(array $rowArr): bool;
}
