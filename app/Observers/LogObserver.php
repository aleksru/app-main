<?php

namespace App\Observers;

use App\Logging\LoggerService;
use Illuminate\Database\Eloquent\Model;

class LogObserver
{
    protected $logger;

    /**
     * LogObserver constructor.
     * @param LoggerService $loggerService
     */
    public function __construct (LoggerService $loggerService)
    {
        $this->logger = $loggerService;
    }

    public function __destruct ()
    {
        $this->logger->saveLog();
    }

    /**
     * @param Model $model
     */
    public function created(Model $model)
    {
        $this->logger->setType($this->logger::TYPE_SET);
        $this->logger->setOriginModel($model);
    }

    /**
     * @param Model $model
     */
    public function updated(Model $model)
    {
        $this->logger->setType($this->logger::TYPE_UPDATE);
        $this->logger->setOriginModel($model);
    }

    /**
     * @param Model $model
     */
    public function deleted(Model $model)
    {
        $this->logger->setType($this->logger::TYPE_UNSET);
        $this->logger->setOriginModel($model);
    }

    /**
     * @param Model $model
     * @param $relationName
     * @param $pivotIds
     * @param $pivotIdsAttributes
     */
    public function pivotAttached(Model $model, $relationName, $pivotIds, $pivotIdsAttributes)
    {
        $this->logger->setCustomChanges(['ADDED PRODUCTS' => $pivotIds]);
        $this->logger->setType($this->logger::TYPE_SET);
        $this->logger->setOriginModel($model);
    }

    /**
     * @param Model $model
     * @param $relationName
     * @param $pivotIds
     */
    public function pivotDetached(Model $model, $relationName, $pivotIds)
    {
        $this->logger->setType($this->logger::TYPE_UNSET);
        $this->logger->setCustomChanges(['DELETE PRODUCTS' => $pivotIds]);
        $this->logger->setOriginModel($model);
    }

    /**
     * @param Model $model
     * @param $relationName
     * @param $pivotIds
     * @param $pivotIdsAttributes
     */
    public function pivotUpdated(Model $model, $relationName, $pivotIds, $pivotIdsAttributes)
    {
        $this->logger->setType($this->logger::TYPE_UPDATE);
        $this->logger->setOriginModel($model);
    }

}