<?php


namespace App\Services\Calls;


use App\ClientCall;
use App\MissedCall;

class CallHandler
{
    /**
     * @var ClientCall
     */
    private $clientCall;

    /**
     * MissedCallsHandler constructor.
     * @param ClientCall $clientCall
     */
    public function __construct(ClientCall $clientCall)
    {
        $this->clientCall = $clientCall;
    }

    /**
     *
     */
    public function handle()
    {
        if($this->clientCall->isMissed() && $this->clientCall->isIncoming()){
            MissedCall::createOnClientCall($this->clientCall);
        }

        if($this->clientCall->isSuccess() && $this->clientCall->isIncoming()){
            MissedCall::excludeOnClientCall($this->clientCall);
        }

        if($this->clientCall->isOutgoing()){
            MissedCall::excludeOnClientCall($this->clientCall);
        }
    }

}