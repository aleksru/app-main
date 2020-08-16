<?php


namespace App\Services\Logistic\Upload\Realizations\Updaters;


use App\Exceptions\Orders\OrderUpdateFromRowException;
use App\Models\Courier;
use App\Models\OtherStatus;
use App\Order;
use App\Services\Couriers\CouriersContainer;
use App\Services\Statuses\OtherStatusesContainer;
use Illuminate\Support\Facades\Log;

class UpdateOrderFromRow extends AbstractUpdateFromRow
{
    /**
     * @var Order
     */
    protected $order;

    /**
     * @var OtherStatusesContainer
     */
    protected $logisticStatusesContainer;

    /**
     * @var CouriersContainer
     */
    protected $couriersContainer;

    /**
     * UpdateRealizationFromRow constructor.
     */
    public function __construct()
    {
        $this->logisticStatusesContainer = new OtherStatusesContainer();
        $this->logisticStatusesContainer->addAll(OtherStatus::typeLogisticStatuses()->get());

        $this->couriersContainer = new CouriersContainer();
        $this->couriersContainer->addAll(Courier::query()->get());
    }

    /**
     * @param Order $order
     */
    public function serOrder(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @throws OrderUpdateFromRowException
     */
    public function update()
    {
        if($this->order->id !== $this->row->getOrderId()){
            throw new OrderUpdateFromRowException(
                "Order #{$this->order->id} not belong Row order_id #{$this->row->getOrderId()}"
            );
        }
        $this->order->unsetEventDispatcher();
        $this->order->timestamps = false;
        if($this->row->getCourierPrice()){
            $this->order->courier_payment = $this->row->getCourierPrice();
        }
        if($status = $this->getStatus()){
            $this->order->logistic_status_id = $status->id;
        }

        if($courier = $this->getCourier()){
            $this->order->courier_id = $courier->id;
        }
        $changes = http_build_query($this->order->getDirty(), null, ',');
        Log::channel('upload_realizations')->error('Update order #' . $this->order->id . ' ' . $changes);

        $this->order->save();
    }

    private function getStatus(): ?OtherStatus
    {
        if($status = $this->row->getStatusText()){
            $result = $this->logisticStatusesContainer->getByName($status);
            if( ! $result ){
                Log::channel('upload_realizations')->error('Status not found - ' . $status);
            }
            return $result;
        }

        return null;
    }


    private function getCourier(): ?Courier
    {
        if($courier = $this->row->getCourierName()){
             $result = $this->couriersContainer->getByName($courier);
             if( ! $result){
                 Log::channel('upload_realizations')->error('Courier not found - ' . $courier);
             }
             return $result;
        }

        return null;
    }
}
