<?php

use Illuminate\Database\Seeder;

class FullAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = \App\Order::where('created_at', '>=', \Carbon\Carbon::today()->subDay(14)->toDateString())->get();
        foreach ($orders as $order){
            $order->full_address = $order->full_address;
            $order->save();
        }
    }
}
