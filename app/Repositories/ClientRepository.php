<?php

namespace App\Repositories;

use App\Client;
use App\Models\ClientPhone;

class ClientRepository
{
    /**
     * Поиск по всем номерам
     *
     * @param string $phone
     * @return mixed
     */
    public function getClientByPhone(string $phone)
    {
        $client = Client::getOnPhone($phone)->first();

        if(!$client) {
            $client = ClientPhone::findByPhone($phone)->first();
            $client ? $client = $client->client : null;
        }

        return $client;
    }
}