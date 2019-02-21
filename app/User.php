<?php

namespace App;

use App\Enums\UserGroupsEnums;
use App\Models\Traits\UserDynamicType;
use App\Models\UserGroup;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, UserDynamicType;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_admin', 'description', 'group_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Атрибуты, которые должны быть преобразованы к базовым типам.
     *
     * @var array
     */
    protected $casts = [
        'is_admin' => 'boolean',
    ];

    /**
     * Роли пользователя
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Группа пользователя
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(UserGroup::class);
    }

    /**
     * Проверка на оператора
     *
     * @return bool
     */
    public function isOperator()
    {
        return $this->getUserGroupName() === UserGroupsEnums::OPERATOR;
    }

    /**
     * Проверка на группу логистов
     *
     * @return bool
     */
    public function isLogist()
    {
        return $this->getUserGroupName() === UserGroupsEnums::LOGIST ;
    }

    /**
     * Проверка на группу склада
     *
     * @return bool
     */
    public function isStock()
    {
        return $this->getUserGroupName() === UserGroupsEnums::STOCK ;
    }

    /**
     * Получение имени группы пользователя
     *
     * @return null|string
     */
    public function getUserGroupName()
    {
        return $this->group ? $this->group->name : null;
    }
}
