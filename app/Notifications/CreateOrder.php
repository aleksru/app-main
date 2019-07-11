<?php

namespace App\Notifications;

use App\Order;
use App\Services\Mango\Commands\SendSms;
use App\Services\Mango\MangoChannel;
use App\Services\Mango\MangoService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;

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
        $text = "Уважаемый клиент! Ваш заказ №{$this->order->id} принят на доставку. Общая сумма {$this->order->fullSum}р. Спасибо за заказ!";
        $store = $this->order->store;
        if($store){
            $text .= " Ваш {$store->name}. {$store->phone}";
        }
        $mangoSms = new SendSms();
        $mangoSms->phone($notifiable->phone)
                    ->smsRender($store->name ?? "")
                    ->text($text)
                    ->fromExtension('101')
                    ->commandId($notifiable->id . rand(1, 9999999));

        (new MangoService())->sendSms($mangoSms);
        Log::error('Смс отправлено ' . $notifiable->phone . $text);
    }
}
