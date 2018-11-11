<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{

    private $roles = [
        [['name' => 'upload_price', 'description' => 'Загрузка прайса']],
        [['name' => 'manager_orders', 'description' => 'Менеджер заказов'], [['name' => 'Просмотр заказов', 'privilege' => 'read_orders'],
                                                                          ['name' => 'Просмотр заказов','privilege' => 'modify_orders']]]
    ];

    public function run()
    {
        foreach ($this->roles as $role) {
            $roleModel = \App\Role::firstOrCreate($role[0]);
            if(isset($role[1])) {
                $idPrevs = [];
                foreach ($role[1] as $prevs) {
                    $idPrevs[] = \App\Privilege::firstOrCreate($prevs)->id;
                }

                $roleModel->privilege()->attach($idPrevs);
            }
        }
    }
}
