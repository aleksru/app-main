<?php


namespace App\Services\RemoteStores;


use App\Enums\RemoteStoresUrls;
use App\Services\RemoteStores\Client\ClientRemoteStore;
use App\Store;
use GuzzleHttp\Psr7\Response;

class RemoteStoreUpdatePrices
{
    /**+
     * @var Store
     */
    private $store;

    /**
     * RemoteStoreUpdatePrices constructor.
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * @return Response
     */
    public function process() : Response
    {
        $client = app(ClientRemoteStore::class);

        return $client->setParams(RemoteStoresUrls::UPDATE_PRICES['method'], $this->generateUrl())->send();
    }

    /**
     * @return string
     */
    private function generateUrl() : string
    {
        return rtrim($this->store->url, '/') . '/' .rtrim(RemoteStoresUrls::UPDATE_PRICES['uri']);
    }
}