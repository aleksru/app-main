<?php


namespace App\Services\Metro\Sources;

use App\Services\Metro\LineStationsData;

interface MetroSourceInterface
{
    public function getLineStations() : LineStationsData;
}