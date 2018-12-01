<?php

use Illuminate\Database\Seeder;

class BaseOrderStatusesSeeder extends Seeder
{
    private $statuses = [
        ['status' =>'Завершен', 'color' => 'default'],
        ['status' =>'В работе', 'color' => 'primary'],
        ['status' =>'Новый', 'color' => 'success'],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->statuses as $status) {
            \App\Models\OrderStatus::firstOrCreate(['status' =>$status['status']], ['color' => $status['color']]);
        }
    }
}
