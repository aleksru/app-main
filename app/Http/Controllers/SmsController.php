<?php


namespace App\Http\Controllers;


use App\Client;
use App\Jobs\SendSms;
use Illuminate\Http\Request;
use App\Services\Mango\Commands\SendSms as MangoSendSms;

class SmsController extends Controller
{
    /**
     * @param Request $request
     * @param Client $client
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendClient(Request $request, Client $client)
    {
        $this->authorize('update', Client::class);
        $text = $request->get('text');
        if( ! $text ) {
            return response()->json(['error' => 'Поле текст не может быть пустым'], 422);
        }
        $mangoTemplateSms = app(MangoSendSms::class);
        $mangoTemplateSms->phone($client->phone)
            ->text($text)
            ->fromExtension('101');

        SendSms::dispatch($client, $mangoTemplateSms);

        return response()->json(['message' => 'Добавлено в очередь на отправку']);
    }
}