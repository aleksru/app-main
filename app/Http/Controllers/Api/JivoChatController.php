<?php


namespace App\Http\Controllers\Api;


use App\Client;
use App\Enums\JivoEventsEnums;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JivoChatController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function webhooks(Request $request)
    {
        //Log::channel('custom')->error($request->all());
        $responseData = ['result' => 'ok'];
        $data = $request->all();
        if($data['event_name'] == JivoEventsEnums::CHAT_ACCEPTED){
            $responseData = array_merge($responseData, $this->chatAccepted($data));
        }

        if($data['event_name'] == JivoEventsEnums::CHAT_UPDATED){
            $responseData = array_merge($responseData, $this->chatUpdated($data));
        }

        if($data['event_name'] == JivoEventsEnums::CLIENT_UPDATED){
            $responseData = array_merge($responseData, $this->clientUpdated($data));
        }

        return response()->json($responseData);
    }

    /**
     * @param array $data
     * @return array
     */
    private function chatAccepted(array $data) : array
    {
        $res = [];
        if($data['visitor'] && $phone = $data['visitor']['phone']){
            $res = array_merge($res, $this->clientCustomData($phone));
        }

        return $res;
    }

    /**
     * @param array $data
     * @return array
     */
    private function chatUpdated(array $data) : array
    {
        $res = [];
        if($data['visitor'] && $phone = $data['visitor']['phone']){
            $res = array_merge($res, $this->clientCustomData($phone));
        }

        return $res;
    }

    /**
     * @param array $data
     * @return array
     */
    private function clientUpdated(array $data) : array
    {
        $res = [];
        if($data['visitor'] && $phone = $data['visitor']['phone']){
            $res = array_merge($res, $this->clientCustomData($phone));
        }

        return $res;
    }

    /**
     * @param string $phone
     * @return array
     */
    private function clientCustomData(string $phone) : array
    {
        $result = [];
        if($client = Client::getClientByPhone($phone)){
            $result['custom_data'] = [
                'title' => 'Ссылка на карточку',
                'link' => route('clients.show', $client->id),
                'content' => $client->name ? $client->name : 'Карточка клиента',
            ];
        }

        return $result;
    }
}