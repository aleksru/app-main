<?php


namespace App\Enums;


class RemoteStoresUrls
{
    const UPDATE_PRICES = ['uri' => 'app-client/public/api/laravel-services/run-update-prices', 'method' => 'GET'];
    const GET_STATE = ['uri' => 'app-client/public/api/laravel-services/state-site', 'method' => 'GET'];
}