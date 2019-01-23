<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserGroupsSeeder extends Seeder
{
    private $groups = [
        ['name' => 'operator', 'description' =>'Операторы'],
    ];

    public function run()
    {
        foreach ($this->groups as $group) {
            \App\Models\UserGroup::firstOrCreate($group);
        }
    }
}