<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{

    private $roles = [
        ['name' =>'read_orders', 'description' =>'Просмотр заказов'],
        ['name' =>'change_orders', 'description' =>'Редактирование заказов'],
        ['name' =>'change_price_list', 'description' =>'Загрузка прайс-листа'],
        ['name' =>'view_stock', 'description' =>'Просмотр склада'],
    ];

    public function run()
    {
        foreach ($this->roles as $role) {
            \App\Role::firstOrCreate($role);
        }
    }
}
