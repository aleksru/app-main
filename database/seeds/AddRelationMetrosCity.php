<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddRelationMetrosCity extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $city = DB::table('cities')->where('name', 'like', 'москва')->first();
        if($city){
            DB::table('metros')->update(['city_id' => $city->id]);
        }
    }
}
