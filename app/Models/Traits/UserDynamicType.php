<?php

namespace App\Models\Traits;


use App\Enums\UserGroupsEnums;
use App\Models\Operator;
use App\Models\StockUser;
use Illuminate\Database\Eloquent\Builder;

trait UserDynamicType
{
    /**
     * Группы и модели
     *
     * @var array
     */
    private $accountRelations = [
        UserGroupsEnums::OPERATOR => Operator::class,
        UserGroupsEnums::STOCK => StockUser::class,
    ];

    /**
     * Получение профиля пользователя в зависимости от его группы
     *
     * @return null|Builder
     */
    public function account()
    {
        if(!$this->group || !array_key_exists($this->group->name, $this->accountRelations)) {
            return null;
        }

        return $this->hasOne($this->accountRelations[$this->group->name]);
    }

    /**
     * Получение связи для модели
     *
     * @return bool|null|string
     */
    public function getRelationByAccount()
    {
        if(!$this->group) {
            return null;
        }

        if (array_key_exists($this->group->name, $this->accountRelations)) {
            return $this->accountRelations[$this->group->name];
        }

        return false;
    }
}