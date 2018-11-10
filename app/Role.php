<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $timestamps = false;

    /**
     * Пользователи роли
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Привилегии роли
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function privilege()
    {
        return $this->belongsToMany(Privilege::class);
    }
}
