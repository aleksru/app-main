<?php


namespace App\Services\Metro;

use App\Helpers\BaseIterableData;

class LineStationsData extends BaseIterableData
{
    public function current() : ?LineStations
    {
        return parent::current();
    }

    public function offsetGet($offset) : ?LineStations
    {
        return parent::offsetGet($offset);
    }

    public function offsetSet($offset, $value)
    {
        if (!$value instanceof LineStations) {
            throw new \InvalidArgumentException("value must be instance of LineStations.");
        }

        parent::offsetSet($offset, $value);
    }
}