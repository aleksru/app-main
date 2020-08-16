<?php


namespace App\Services\Logistic\Upload\Realizations\Updaters;


use App\Exceptions\Realizations\RealizationsUpdateFromRowException;
use App\Models\Realization;
use Illuminate\Support\Facades\Log;

class UpdateRealizationFromRow extends AbstractUpdateFromRow
{
    /**
     * @var Realization
     */
    protected $realization;


    public function setRealization(Realization $realization)
    {
        $this->realization = $realization;
    }

    /**
     * @throws RealizationsUpdateFromRowException
     */
    public function update()
    {
        if($this->realization->order_id !== $this->row->getOrderId()){
            throw new RealizationsUpdateFromRowException(
        "Realization order_id #{$this->realization->order_id} not belong Row order_id #{$this->row->getOrderId()}"
            );
        }
        $this->realization->unsetEventDispatcher();
        $this->realization->timestamps = false;
        $this->realization->imei = $this->row->getProductImei();
        $this->realization->price = number_format( (float) $this->row->getProductPrice(), 2, '.', '');
        $this->realization->price_opt = number_format( (float) $this->row->getSupplierPrice(), 2, '.', '');
        $changes = http_build_query($this->realization->getDirty(), null, ',');
        Log::channel('upload_realizations')->error('Update order_id #'.$this->realization->order_id.' realization #' .
            $this->realization->id. ' ' . $changes);
        $this->realization->save();
    }

}
