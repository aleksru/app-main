<?php


namespace App\Services\Metro\Sources\HH;

use App\Models\City;
use App\Models\Metro as MetroModel;
use App\Services\Metro\Sources\MetroSourceInterface;
use GuzzleHttp\Client;

class Metro implements MetroSourceInterface
{
    /**
     * @var City
     */
    private $city;

    /**
     * Metro constructor.
     * @param City $city
     */
    public function __construct(City $city)
    {
        $this->city = $city;
    }

    /**
     *
     */
    public function update()
    {
        $data = $this->getData();
        foreach ($data['lines'] as $line){
            foreach ($line['stations'] as $station){
                MetroModel::firstOrCreate([
                    'name' => $station['name'],
                    'city_id' => $this->city->id,
                ]);
            }
        }
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