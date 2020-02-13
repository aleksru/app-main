<?php

namespace App\Jobs;

use App\Client;
use App\ClientCall;
use App\Enums\MangoCallEnums;
use App\Models\Operator;
use App\Repositories\CallsRepository;
use App\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SaveCall implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
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
        $data = $this->data;

        //входящий звонок
        if($data['call_direction'] === MangoCallEnums::CALL_DIRECTION_INCOMING) {
            $callRepository = new CallsRepository();
            //Log::channel('custom')->error($data);
            //пропущенный
            if($data['entry_result'] === MangoCallEnums::CALL_RESULT_MISSED) {
                $isFirst = false;
                $client = Client::getClientByPhone($data['from']['number']);
                $store = Store::where('phone', $data['line_number'])->first();
                if(!$client){
                    $isFirst = true;
                    $client = Client::create(['phone' => $data['from']['number']]);
                }
                if($callRepository->getCountCallsFromNumber($data['from']['number']) === 0){
                    $isFirst = true;
                }
                Log::channel('custom')->error('///////////////////////////пропущенный');
                Log::channel('custom')->error($data);

                ClientCall::create([
                    'type' => ClientCall::incomingCall,
                    'from_number' => $data['from']['number'] ?? null,
                    'to_number' => $data['to']['number'] ?? null,
                    'line_number' => $data['line_number'] ?? null,
                    'call_create_time' => $data['create_time'],
                    'call_end_time' => $data['end_time'],
                    'client_id' => $client->id ?? null,
                    'status_call' => $data['entry_result'],
                    'store_id' => $store->id ?? null,
                    'extension' => $data['to']['extension'] ?? null,
                    'is_first' => $isFirst,
                    'talk_time' => $data['talk_time'] ?? 0
                ]);
            }

            //успешный
            if($data['entry_result'] === MangoCallEnums::CALL_RESULT_SUCCESS) {
                $getOperator = $data['to']['number'] ? Operator::getOperatorBySipLogin(explode(':', $data['to']['number'])[1]) : null;
                $store = Store::where('phone', $data['line_number'])->first();
                $client = Client::getClientByPhone($data['from']['number']);
                if(!$client){
                    $client = Client::create(['phone' => $data['from']['number']]);
                }
                if($callRepository->getCountCallsFromNumber($data['from']['number']) === 0){
                    $isFirst = true;
                }
                ClientCall::create([
                    'type' => ClientCall::incomingCall,
                    'from_number' => $data['from']['number'] ?? null,
                    'to_number' => $data['to']['number'] ?? null,
                    'line_number' => $data['line_number'] ?? null,
                    'call_create_time' => $data['create_time'],
                    'call_end_time' => $data['end_time'],
                    'client_id' => $client->id ?? null,
                    'status_call' => $data['entry_result'],
                    'store_id' => $store->id ?? null,
                    'operator_id' => $getOperator ? $getOperator->id : null,
                    'operator_text' => $data['to']['number'] ?? null,
                    'extension' => $data['to']['extension'] ?? null,
                    'is_first' => $isFirst ?? false,
                    'talk_time' => $data['talk_time'] ?? 0
                ]);
            }
        }

        //исходящий
        if($data['call_direction'] === MangoCallEnums::CALL_DIRECTION_OUTCOMING) {
            if (! isset($data['from']['number'][1])){
                return null;
            }
            $operator = Operator::getOperatorBySipLogin(explode(':', $data['from']['number'])[1]);
            $client = Client::getClientByPhone($data['to']['number']);

            ClientCall::create([
                'type' => ClientCall::outgoingCall,
                'from_number' => $data['to']['number'] ?? null,
                'to_number' => $data['to']['number'] ?? null,
                'line_number' => $data['line_number'] ?? null,
                'call_create_time' => $data['create_time'],
                'call_end_time' => $data['end_time'],
                'operator_text' => $data['from']['number'] ?? null,
                'client_id' => $client->id ?? null,
                'status_call' => $data['entry_result'],
                'operator_id' => $operator->id ?? null,
                'talk_time' => $data['talk_time'] ?? 0
            ]);
        }
    }
}
