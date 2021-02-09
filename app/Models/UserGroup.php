<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserGroup extends Model
{
    use Notifiable;

    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class, 'group_id', 'id');
    }

    public function getMembers()
    {
        return $this->users;
    }
}
