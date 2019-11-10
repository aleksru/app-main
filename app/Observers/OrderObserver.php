<?php

namespace App\Observers;

use App\Events\CreatedOrderEvent;
use App\Events\UpdatedOrderEvent;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\Report;
use App\Jobs\SendLogistGoogleTable;
use App\Jobs\SmsAction;
use App\Models\OrderStatus;
use App\Notifications\CreateOrder;
use App\Order;
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
            $statusConfirm = Cache::remember('ID_ORDER_STATUS_CONFIRM', Carbon::now()->addHours(4), function (){
                return OrderStatus::getIdStatusConfirm();
            });

            $statusNoReach = Cache::remember('ID_ORDER_STATUS_MISSED_CALL', Carbon::now()->addHours(4), function (){
                return OrderStatus::getIdStatusForType(OrderStatus::STATUS_MISSED_PREFIX);
            });

            if($order->status_id == $statusNoReach){
                dispatch(new SmsAction($order->client, new SmsActionNoReach($order->client, $order)));
            }

            if($order->status_id == $statusConfirm){
                if(!$order->flag_send_sms){
                    $order->client->notify(new CreateOrder($order));
                    $order->flag_send_sms = true;
                }
                $logistOrderData = app(OrderLogistData::class, ['order' => $order]);
                dispatch(new SendLogistGoogleTable($logistOrderData))->onQueue('google-tables');
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
