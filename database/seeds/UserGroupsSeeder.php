<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserGroupsSeeder extends Seeder
{
    private $groups = [
        ['name' => \App\Enums\UserGroupsEnums::OPERATOR, 'description' =>'Операторы'],
        ['name' => \App\Enums\UserGroupsEnums::STOCK, 'description' =>'Склад'],
        ['name' => \App\Enums\UserGroupsEnums::LOGIST, 'description' =>'Логистика'],
    ];

    public function run()
    {
        foreach ($this->groups as $group) {
            \App\Models\UserGroup::firstOrCreate($group);
        }
    }
}