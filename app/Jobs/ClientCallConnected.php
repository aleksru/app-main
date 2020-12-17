<?php

namespace App\Jobs;

use App\Client;
use App\Events\OperatorCallConnected;
use App\Models\Operator;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class ClientCallConnected implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = Client::getClientByPhone($this->data['from']['number']);
        $operator = Operator::getOperatorBySipLogin($this->data['to']['number']);
        if($operator && $client && $user = $operator->user){
            $lastOrder = $client->orders()->max('id');
            event(new OperatorCallConnected($user, $client, $lastOrder));
            Log::channel('calls_connected')->error(['Operator: ' . $operator->sip_login . ', from number: '. $client->phone . ' connected']);
        }else{
            if(!$operator){
                Log::channel('calls_connected')->error(['Error: Operator: ' . $this->data['to']['number'] . ' Not found!']);
            }
            if(!$client){
                Log::channel('calls_connected')->error(['Error: Client: ' . $this->data['from']['number'] . ' Not found!']);
            }
            if(!$operator->user){
                Log::channel('calls_connected')->error(['Error: Operator: ' . $operator->id . ' account user Not found!']);
            }
        }
    }
}
