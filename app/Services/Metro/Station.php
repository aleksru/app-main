<?php


namespace App\Services\Metro;


class Station
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $lat;

    /**
     * @var float
     */
    private $lon;

    /**
     * Station constructor.
     * @param string $name
     * @param float|null $lat
     * @param float|null $lon
     */
    public function __construct(string $name, float $lat = null, float $lon = null)
    {
        $this->name = $name;
        $this->lat = $lat;
        $this->lon = $lon;
    }


    /**
     * @return mixed
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getLat() : ?float
    {
        return $this->lat;
    }

    /**
     * @param mixed $lat
     */
    public function setLat(float $lat)
    {
        $this->lat = $lat;
    }

    /**
     * @return mixed
     */
    public function getLon() : ?float
    {
        return $this->lon;
    }

    /**
     * @param mixed $lon
     */
    public function setLon(float $lon)
    {
        $this->lon = $lon;
    }

    public function setLatLon(float $lat, float $lon)
    {
        $this->setLat($lat);
        $this->setLon($lon);
    }
}