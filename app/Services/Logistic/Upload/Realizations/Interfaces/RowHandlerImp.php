<?php


namespace App\Services\Logistic\Upload\Realizations\Interfaces;


use App\Exceptions\Orders\OrderNotFoundException;
use App\Exceptions\Realizations\Upload\ErrorUpdateEntityException;
use App\Services\Logistic\Upload\Realizations\Data\Row;

interface RowHandlerImp
{
    /**
     * @param Row $row
     * @throws OrderNotFoundException
     */
    public function setRow(Row $row): void;

    /**
     * @throws ErrorUpdateEntityException
     */
    public function handle();
}
