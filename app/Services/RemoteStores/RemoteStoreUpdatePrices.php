<?php


namespace App\Services\RemoteStores;


use App\Enums\RemoteStoresUrls;
use GuzzleHttp\Psr7\Response;

class RemoteStoreUpdatePrices extends BaseRemoteStore
{
    /**
     * @return Response
     */
    public function process() : Response
    {
        return $this->client->setParams(RemoteStoresUrls::UPDATE_PRICES['method'], $this->generateUrl())->send();
    }

    /**
     * @return string
     */
    protected function getTaskUri() : string
    {
        return RemoteStoresUrls::UPDATE_PRICES['uri'];
    }
}