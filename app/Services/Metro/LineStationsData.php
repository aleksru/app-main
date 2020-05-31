<?php


namespace App\Services\Metro;


class LineStationsData
{
    protected $lineStations = [];

    public function addLineStations(LineStations $lineStations)
    {
        $this->lineStations[] = $lineStations;
    }

    public function getLineStationsIterable()
    {
        foreach ($this->lineStations as $lineStation){
            yield $lineStation;
        }
    }

    /**
     * @return LineStations|null
     */
    public function popLineStation() : ?LineStations
    {
        return array_pop($this->lineStations);
    }
}