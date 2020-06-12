<?php

namespace App\Observers;

use App\Events\CreatedOrderEvent;
use App\Events\OrderConfirmedEvent;
use App\Events\UpdatedOrderEvent;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\Report;
use App\Jobs\SendLogistGoogleTable;
use App\Jobs\SmsAction;
use App\Models\OrderStatus;
use App\Notifications\CreateOrder;
use App\Order;
use App\Repositories\OrderStatusRepository;
use App\Services\Actions\SmsActionNoReach;
use App\Services\Google\Sheets\Data\OrderLogistData;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class OrderObserver
{
    /**
     * Handle the order "created" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        event(new CreatedOrderEvent($order->load('status', 'client', 'operator', 'store')));
    }

    /**
     * Handle the order "updated" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {

    }

    public function updating(Order $order)
    {
        if($order->getAttributeValue('status_id') != $order->getOriginal('status_id')) {
            $statusConfirm = app(OrderStatusRepository::class)->getIdsStatusConfirmed();
            $statusNoReach = app(OrderStatusRepository::class)->getIdStatusMissedOutCall();
            $statusComplaint = app(OrderStatusRepository::class)->getIdsStatusComplaining();

            if($order->status_id == $statusNoReach){
                dispatch(new SmsAction($order->client, new SmsActionNoReach($order->client, $order)));
            }

            if($order->status_id == $statusConfirm){
                if(!$order->flag_send_sms){
                    $order->client->notify(new CreateOrder($order));
                    $order->flag_send_sms = true;
                }
                if($order->client && $order->client->countOrdersInStore($order->store_id) > 1){
                    if ($order->client->isLoyalStore($order->store_id)){
                        $order->comment = $order->comment . "\n Лояльный клиент(Были успешные заказы, нет жалоб)";
                    }
                    if ($order->client->isStoreComplaint($order->store_id)){
                        $order->comment = $order->comment . "\n Негативный клиент(Были жалобы)";
                    }
                }
                $order->confirmed_at = Carbon::now();
                $logistOrderData = app(OrderLogistData::class, ['order' => $order]);
                dispatch(new SendLogistGoogleTable($logistOrderData))->onQueue('google-tables');
                event(new OrderConfirmedEvent($order));

                if($order->store_id  && $order->client){
                    $order->client->addSuccessStore($order->store_id);
                }
            }

            if($order->status_id == $statusComplaint){
                if($order->store_id  && $order->client){
                    $order->client->addComplaintStore($order->store_id);
                }
            }
        }

//        if(!$order->getOriginal('operator_id') && $order->getAttributeValue('operator_id')) {
//            event(new UpdatedOrderEvent($order->load('status', 'client', 'operator', 'store')));
//        }
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        //
    }

    /**
     * Handle the order "restored" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the order "force deleted" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}
