<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserGroup extends Model
{
    use Notifiable;

    protected $guarded = ['id'];
}
