<?php


namespace App\Services\Metro\Sources\HH;

use App\Models\City;
use App\Services\Metro\LineStations;
use App\Services\Metro\LineStationsData;
use App\Services\Metro\Sources\MetroSourceInterface;
use GuzzleHttp\Client;

class Metro implements MetroSourceInterface
{
    /**
     * @var City
     */
    private $city;

    /**
     * @var LineStationsData
     */
    private $lineStationsData;

    /**
     * Metro constructor.
     * @param City $city
     */
    public function __construct(City $city)
    {
        $this->city = $city;
        $this->lineStationsData = new LineStationsData();
    }

    /**
     *
     */
    public function getLineStations() : LineStationsData
    {
        $data = $this->getData();
        foreach ($data['lines'] as $line){
            $lineStations = new LineStations($line['name'], $line['hex_color']);
            foreach ($line['stations'] as $station){
                $lineStations->addStation(
                    LineStations::stationFactory(
                        $station['name'],
                            $station['lat'] ?? null,
                            $station['lng'] ?? null)
                );
            }
            $this->lineStationsData->addLineStations($lineStations);
        }

        return $this->lineStationsData;
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function getLink() : string
    {
        $cityName = $this->city->name;

        if(!array_key_exists($cityName, config('metro.aliases'))){
            throw new \Exception('City not found in config!');
        }
        $alias = config('metro.aliases')[$cityName];

        return config('metro.links.hh')[$alias];
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function getData() : array
    {
        $client = new Client();
        $res = $client->get($this->getLink());
        if($res->getStatusCode() > 400){
            throw new \Exception('Unable to retrieve data');
        }

        return json_decode($res->getBody()->getContents(), true);
    }
}