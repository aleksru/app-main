<?php


namespace App\Services\Actions;


use App\Client;
use App\Order;
use App\Services\Mango\Commands\SendSms;
use Illuminate\Support\Str;

class SmsActionNoReach implements ActionInterface
{
    /**
     *
     */
    const NAME = 'ACTION_DONT_REACH';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Order
     */
    private $order;

    /**
     * SmsActionNoReach constructor.
     * @param Client $client
     */
    public function __construct(Client $client, Order $order)
    {
        $this->client = $client;
        $this->order = $order;
    }

    public function isCheckAction() : bool
    {
        return config('sms.actions.sms_action_no_reach');
    }

    public function isCheckParams() : bool
    {
        return $this->client->sms()->where('action', self::NAME)->count() === 0;
    }

    /**
     * @return SendSms
     * @throws \Exception
     */
    public function getMessage() : SendSms
    {
        $store = $this->order->store;
        if(!$store){
            throw new \Exception('SmsActionNoReach store not found in order: ' . $this->order->id . ' SMS not send!');
        }

        $url = $store->url ?? 'https://' . $store->name;
        $text = "Мы знаем, что Вы хотели оформить заказ на гаджет, но не завершили начатое... А хотите ещё ПОДАРОК на 1790? Только сегодня и завтра! Ну что, интересно? Просто спросите нашего менеджера о подарке ) Ваш {$url} +{$store->phone}";
        $commandId = Str::uuid();
        $mangoSms = new SendSms();
        $mangoSms->phone($this->client->phone)
            ->smsRender($store->name ?? "")
            ->text($text)
            ->fromExtension('101')
            ->commandId($commandId);

        return $mangoSms;
    }

    /**
     * @return String
     */
    public static function getNameAction() : String
    {
        return self::NAME;
    }
}