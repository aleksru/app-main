<?php

namespace App\Jobs;

use App\Client;
use App\Services\Mango\MangoService;
use App\Services\Operator\Calls\Commands\RouteToReclamation;
use App\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RouteReclamationCall implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $numberClient;
    protected $numberStore;
    protected $callId;

    /**
     * RouteReclamationCall constructor.
     * @param string $numberClient
     * @param string $numberStore
     * @param string $callId
     */
    public function __construct(string $numberClient, string $numberStore, string $callId)
    {
        $this->numberClient = $numberClient;
        $this->numberStore  = $numberStore;
        $this->callId       = $callId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = Client::getClientByPhone($this->numberClient);
        $store = Store::getStoreByPhoneNumber($this->numberStore);
        if($client && $store){
            $isComplaint = $client->isStoreComplaint($store->id);
            if($isComplaint){
                $mango = new MangoService();
                $mango->route(new RouteToReclamation($this->callId));
            }
        }
    }
}
