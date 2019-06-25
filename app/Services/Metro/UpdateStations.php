<?php


namespace App\Services\Metro;


use App\Models\Metro;
use GuzzleHttp\Client;

class UpdateStations
{
    /**
     *
     */
    public function update()
    {
        $data = $this->getData();
        foreach ($data['lines'] as $line){
            foreach ($line['stations'] as $station){
                Metro::firstOrCreate(['name' => $station['name']]);
            }
        }

    }

    /**
     * @return array
     * @throws \Exception
     */
    private function getData() : array
    {
        $client = new Client();
        $res = $client->get('https://api.hh.ru/metro/1');
        if($res->getStatusCode() > 400){
            throw new \Exception('Unable to retrieve data');
        }

        return json_decode($res->getBody()->getContents(), true);
    }
}