<?php

namespace App\Notifications;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ClientCallBack extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Order
     */
    private $order;

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
        return ['database', 'broadcast'];
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
            'content' => (view('front.notifications.user.call_back', [
                'orderId' => $this->order->id,
                'communication_time' => $this->order->communication_time->format('d.m H:i')
            ])->render())
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'invoice_id' => $this->order->id,
            'test' => 'test data',
        ]);
    }
}
