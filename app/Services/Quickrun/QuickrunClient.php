<?php


namespace App\Services\Quickrun;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Log;

class QuickrunClient
{
    /**
     *
     */
    const BASE_URL = 'https://www.quickrun.ru/api/1.0/';

    /**
     * @var Client
     */
    private $client;

    /**
     * QuickrunClient constructor.
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => self::BASE_URL,
            'headers' => [
                'Authorization' => env('QUICKRUN_KEY'),
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    /**
     * @param string $uri
     * @param array $query
     * @return Response|null
     */
    public function get(string $uri, array $query = [])
    {
        $request = new Request('GET', $uri, [
            'query' => $query
        ]);

        return $this->send($request);
    }

    /**
     * @param string $uri
     * @param array $data
     * @return Response|null
     */
    public function post(string $uri, array $data = [])
    {
        $request = new Request('POST', $uri, [], json_encode($data));

        return $this->send($request);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    private function send(Request $request)
    {
        try{
            $response = $this->client->send($request);
            $data = json_decode($response->getBody()->getContents(), true);

            if(!$data['success']){
                Log::channel('quickrun')->error($data['error']);
                throw new \Exception($data);
            }

            return $data;
        }catch (\Exception $e){
            Log::channel('quickrun')->error($e);
            throw new \Exception($e);
        }
    }
}