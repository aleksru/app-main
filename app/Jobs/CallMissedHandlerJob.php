<?php

namespace App\Jobs;

use App\ClientCall;
use App\MissedCall;
use App\Services\Calls\CallHandler;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CallMissedHandlerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var ClientCall
     */
    private $clientCall;

    /**
     * MissedCallHandler constructor.
     * @param int $idClientCall
     */
    public function __construct(int $idClientCall)
    {
        $this->clientCall = ClientCall::findOrFail($idClientCall);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new CallHandler($this->clientCall))->handle();
    }
}
