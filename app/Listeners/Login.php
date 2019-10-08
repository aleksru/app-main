<?php


namespace App\Listeners;


use App\Services\UserTimeControl\TimeControl;
use Illuminate\Auth\Events\Login as LoginEvent;

class Login
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
     * @param \Illuminate\Auth\Events\Login $event
     */
    public function handle(LoginEvent $event)
    {
        $this->timeControl->logon($event->user);
    }
}