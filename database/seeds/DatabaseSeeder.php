<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesSeeder::class);
        $this->call(BaseOrderStatusesSeeder::class);
        //$this->call(MetrosSeeder::class);
        $this->call(UserGroupsSeeder::class);
    }
}
