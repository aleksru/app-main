<?php

namespace App\Jobs;

use App\Order;
use App\Services\Google\Sheets\Data\OrderLogistData;
use App\Services\Google\Sheets\GoogleSheets;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendLogistGoogleTable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Order
     */
    private $orderData;

    /**
     * SendLogistGoogleTable constructor.
     * @param OrderLogistData $orderLogistData
     */
    public function __construct(OrderLogistData $orderLogistData)
    {
        $this->orderData = $orderLogistData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->orderData->prepareData();
        foreach ($data as $value){
            app(GoogleSheets::class)->writeRow(array_values($value));
        }
    }
}
