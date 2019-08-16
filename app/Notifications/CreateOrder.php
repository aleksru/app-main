<?php

namespace App\Notifications;

use App\Events\SendSmsClient;
use App\Order;
use App\Services\Mango\Commands\SendSms;
use App\Services\Mango\MangoChannel;
use App\Services\Mango\MangoService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CreateOrder extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var string
     */
    private $text;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        if($this->order->fullSum == 0){
            $this->delay(Carbon::now()->addMinutes(5));
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [MangoChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
//        return (new MailMessage)
//                    ->line('The introduction to the notification.')
//                    ->action('Notification Action', url('/'))
//                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * @param $notifiable
     * @return array
     * @throws \Exception
     */
    public function toMangoSms($notifiable)
    {
        if(!$notifiable->phone){
            throw new \Exception('Error send sms. Notifialble not found phone number');
        }
        if($this->order->fullSum == 0){
            Log::error('Error sending sms. Number ' . $notifiable->phone . ' order:' . $this->order->id . ' products not found');
            return false;
        }
        $text = "Уважаемый клиент! Ваш заказ №{$this->order->id} принят на доставку. Общая сумма {$this->order->fullSum}р. Спасибо за заказ!";
        $store = $this->order->store;
        if($store){
            $text .= " Ваш {$store->name}. {$store->phone}";
        }
        $commandId = Str::uuid();
        $mangoSms = new SendSms();
        $mangoSms->phone($notifiable->phone)
                    ->smsRender($store->name ?? "")
                    ->text($text)
                    ->fromExtension('101')
                    ->commandId($commandId);

        $res = (new MangoService())->sendSms($mangoSms);
        event(new SendSmsClient($mangoSms, $this->order, (int)$res['result'] ?? null));
    }
}
