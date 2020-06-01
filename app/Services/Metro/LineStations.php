<?php


namespace App\Services\Metro;


class LineStations extends Line
{
    /**
     * @var array Station
     */
    protected $stations = [];

    /**
     * @param Station $station
     */
    public function addStation(Station $station)
    {
        $this->stations[] = $station;
    }

    /**
     * @param array $stations
     */
    public function addAllStations(array $stations)
    {
        foreach ($stations as $station){
            if( ! ($station instanceof Station) ){
                throw new \InvalidArgumentException('Item not instance App\Services\Metro\Station!');
            }
        }
        $this->stations = array_merge($this->stations, $stations);
    }

    /**
     * @return array
     */
    public function getStations() : array
    {
        return $this->stations;
    }

    /**
     * @param int $index
     * @return Station|null
     */
    public function getStation(int $index) : ?Station
    {
        return $this->stations[$index] ?? null;
    }

    /**
     * @return int
     */
    public function getCountStations(): int
    {
        return count($this->stations);
    }

    /**
     * @return Station
     */
    public function popStation() : ?Station
    {
        return array_pop($this->stations);
    }

    /**
     * @param string $name
     * @param float|null $lat
     * @param float|null $lon
     * @return Station
     */
    public static function stationFactory(string $name, float $lat = null, float $lon = null) : Station
    {
        return new Station($name, $lat, $lon);
    }
}