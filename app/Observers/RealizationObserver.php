<?php

namespace App\Observers;

use App\Realization;

class RealizationObserver
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
     * Handle the realization "created" event.
     *
     * @param  \App\Realization  $realization
     * @return void
     */
    public function created(Realization $realization)
    {
        //
    }

    /**
     * Handle the realization "updated" event.
     *
     * @param  \App\Realization  $realization
     * @return void
     */
    public function updated(Realization $realization)
    {
        //
    }

    /**
     * Handle the realization "deleted" event.
     *
     * @param  \App\Realization  $realization
     * @return void
     */
    public function deleted(Realization $realization)
    {
        //
    }

    /**
     * Handle the realization "restored" event.
     *
     * @param  \App\Realization  $realization
     * @return void
     */
    public function restored(Realization $realization)
    {
        //
    }

    /**
     * Handle the realization "force deleted" event.
     *
     * @param  \App\Realization  $realization
     * @return void
     */
    public function forceDeleted(Realization $realization)
    {
        //
    }
}
