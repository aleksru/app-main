<?php


namespace App\Http\Controllers;


use App\Client;
use App\Jobs\SendSms;
use Illuminate\Http\Request;
use App\Services\Mango\Commands\SendSms as MangoSendSms;

class SmsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('front.sms.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendDistribution(Request $request)
    {
        $clients = Client::whereIn('id', $request->get('clientIds'))->get();
        foreach ($clients as $client) {
            $mangoTemplateSms = app(MangoSendSms::class);
            $mangoTemplateSms->phone($client->phone)
                ->text($request->get('text'))
                ->fromExtension('101');
            SendSms::dispatch($client, $mangoTemplateSms);
        }

        return response()->json(['message' => 'Добавлено в очередь на отправку!'], 200);
    }

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