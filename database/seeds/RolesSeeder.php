<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{

    private $roles = [
        ['name' => \App\Enums\RoleOrderEnums::READ_ORDER, 'description' =>'Просмотр заказов'],
        ['name' => \App\Enums\RoleOrderEnums::CHANGE_ORDER, 'description' =>'Редактирование заказов'],
        ['name' =>'change_price_list', 'description' =>'Загрузка прайс-листа'],
        ['name' =>'view_stock', 'description' =>'Просмотр склада'],
        ['name' =>'view_logistics', 'description' =>'Просмотр логистики'],
    ];

    public function run()
    {
        foreach ($this->roles as $role) {
            \App\Role::firstOrCreate($role);
        }
    }
}
