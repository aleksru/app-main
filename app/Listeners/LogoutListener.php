<?php


namespace App\Listeners;


use App\Services\UserTimeControl\TimeControl;
use Illuminate\Auth\Events\Logout;

class LogoutListener
{
    /**
     * @var TimeControl
     */
    private $timeControl;

    /**
     * Login constructor.
     * @param TimeControl $timeControl
     */
    public function __construct(TimeControl $timeControl)
    {
        $this->timeControl = $timeControl;
    }

    /**
     * @param Logout $event
     */
    public function handle(Logout $event)
    {
        $this->timeControl->logout($event->user);
    }
}