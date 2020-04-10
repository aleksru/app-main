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
        ['name' => \App\Enums\RoleOrderEnums::HEAD_STOCK, 'description' =>'Начальник склада'],
        ['name' => \App\Enums\RoleOrderEnums::FULL_LOGISTIC, 'description' =>'Полный доступ логистика'],
    ];

    public function run()
    {
        foreach ($this->roles as $role) {
            \App\Role::firstOrCreate($role);
        }
    }
}
