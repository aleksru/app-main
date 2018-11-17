<?php

use Illuminate\Database\Seeder;

class BaseOrderStatusesSeeder extends Seeder
{
    private $statuses = [
        ['status' =>'Завершен'],
        ['status' =>'В работе'],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->statuses as $status) {
            \App\Models\OrderStatus::firstOrCreate($status);
        }
    }
}
