<?php


namespace App\Services\Mango;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;

class MangoClient
{
    /**
     * @var string
     */
    private $data = '';

    /**
     * @var string
     */
    private $uri;

    /**
     * MangoClient constructor.
     * @param $data
     * @param string $uri
     */
    public function __construct($data, string $uri)
    {
        $this->data = json_encode($data);
        $this->uri = $uri;
    }

    /**
     * @return array
     */
    public function send() : array
    {
        try{
            $ch = curl_init($this->genUrl());
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->renderBody());
            $response = curl_exec($ch);
            curl_close($ch);

            $res = json_decode($response, true);
            if( ! is_array($res) ){
                Log::error(['Mango response not valid!', $res, $this->data]);
                throw new \Exception();
            }
            return $res;
        }catch (\Exception $e){
            Log::error($e);
            throw new \Exception($e);
        }
    }

    /**
     * @return string
     */
    private function renderSign() : string
    {
        return hash('sha256',
            (config('mango.api_key') . $this->data . config('mango.api_salt')));
    }

    /**
     * @return string
     */
    private function genUrl() : string
    {
        return trim(config('mango.api_url'), '/') . '/' . trim($this->uri, '/');
    }

    /**
     * @return string
     */
    private function renderBody() : string
    {
        return http_build_query([
            'vpbx_api_key' => config('mango.api_key'),
            'sign' => $this->renderSign(),
            'json' => $this->data
        ]);
    }
}
