<?php

namespace App\Observers;

use App\Logging\LoggerService;
use App\Order;

class OrderObserver
{
    protected $logger;

    public function __construct (LoggerService $loggerService)
    {
        $this->logger = $loggerService;
    }

    public function __destruct ()
    {
        $this->logger->saveLog();
    }

    /**
     * @param Order $order
     */
    public function created(Order $order)
    {
        $this->logger->setOriginModel($order);
        $this->logger->setType($this->logger::TYPE_SET);
    }

    /**
     * @param Order $order
     */
    public function updated(Order $order)
    {
        $this->logger->setType($this->logger::TYPE_UPDATE);
        $this->logger->setOriginModel($order);
    }

    /**
     * @param Order $order
     */
    public function deleted(Order $order)
    {
        $this->logger->setType($this->logger::TYPE_UPDATE);
        $this->logger->setOriginModel($order);
    }

}
