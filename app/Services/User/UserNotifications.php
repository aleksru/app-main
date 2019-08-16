<?php
namespace App\Services\User;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class UserNotifications
{
    /**
     * @var User
     */
    private $user;

    /**
     * UserNotifications constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return Collection
     */
    public function getUnreadNotifications() : Collection
    {
        return $this->user->unreadNotifications->merge($this->getUserGroup() ?
                                    $this->getUserGroup()->unreadNotifications : collect());
    }

    /**
     * @return mixed
     */
    private function getUserGroup()
    {
        return Cache::remember("user_{$this->user->id}.group", Carbon::now()->addHours(2), function (){
            return $this->user->group;
        });
    }
}