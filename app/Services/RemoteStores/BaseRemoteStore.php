<?php


namespace App\Services\RemoteStores;

use App\Enums\RemoteStoresUrls;
use App\Services\RemoteStores\Client\ClientRemoteStore;
use App\Store;
use GuzzleHttp\Psr7\Response;

abstract class BaseRemoteStore
{
    /**+
     * @var Store
     */
    private $store;

    /**
     * @var ClientRemoteStore
     */
    protected $client;

    /**
     * RemoteStoreUpdatePrices constructor.
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
        $this->client = app(ClientRemoteStore::class);
    }

    /**
     * @return string
     */
    protected function generateUrl() : string
    {
        return rtrim($this->store->url, '/') . '/' .rtrim($this->getTaskUri());
    }

    /**
     * @return Response
     */
    abstract public function process() : Response;

    /**
     * @return string
     */
    abstract protected function getTaskUri() : string;
}