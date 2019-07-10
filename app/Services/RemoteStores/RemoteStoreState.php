<?php


namespace App\Services\RemoteStores;

use App\Enums\RemoteStoresUrls;
use GuzzleHttp\Psr7\Response;

class RemoteStoreState extends BaseRemoteStore
{
    /**
     * @return Response
     */
    public function process() : Response
    {
        return $this->client->setParams(RemoteStoresUrls::GET_STATE['method'], $this->generateUrl())->send();
    }

    /**
     * @return string
     */
    protected function getTaskUri() : string
    {
        return RemoteStoresUrls::GET_STATE['uri'];
    }
}