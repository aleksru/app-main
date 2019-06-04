<?php


namespace App\Services\RemoteStores\Client;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class ClientRemoteStore
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Request
     */
    private $request;

    /**
     * ClientRemoteStore constructor.
     */
    public function __construct()
    {
        $this->client = (new Client());
    }

    /**
     * @param $method
     * @param $uri
     * @param array $headers
     * @return ClientRemoteStore
     */
    public function setParams($method, $uri, array $headers = []) : ClientRemoteStore
    {
        $headers['Authorization'] = env('APP_API_KEY');
        $this->request = (new Request($method, $uri, $headers));

        return $this;
    }

    /**
     * @param array $options
     * @return Response
     */
    public function send(array $options = []) : Response
    {
        $options['allow_redirects'] = true;

        return $this->client->send($this->request, $options);
    }
}