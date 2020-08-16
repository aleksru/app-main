<?php


namespace App\Services\Logistic\Upload\Realizations\Updaters;

use App\Services\Logistic\Upload\Realizations\Data\Row;

abstract class AbstractUpdateFromRow
{
    /**
     * @var Row
     */
    protected $row;

    /**
     * AbstractUpdateFromRow constructor.
     * @param Row $row
     */
    public function setRow(Row $row)
    {
        $this->row = $row;
    }

    abstract public function update();
}
