<?php

namespace App\Models\Traits;


use App\Enums\UserGroupsEnums;
use App\Models\Operator;
use Illuminate\Database\Eloquent\Builder;

trait UserDynamicType
{
    private $accountRelations = [
        UserGroupsEnums::OPERATOR => Operator::class,
    ];
    /**
     * Получение профиля пользователя в зависимости от его группы
     *
     * @return null|Builder
     */
    public function account()
    {
        if(!$this->group) {
            return null;
        }

        switch ($this->group->name){
            case UserGroupsEnums::OPERATOR:
                return $this->hasOne(Operator::class);
            default: null;
        }
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