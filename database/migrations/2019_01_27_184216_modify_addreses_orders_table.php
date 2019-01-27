<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyAddresesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->string('address_city')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_home')->nullable();
            $table->string('address_apartment')->nullable();
            $table->string('address_other')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('address')->nullable();
            $table->dropColumn(['address_city', 'address_street', 'address_home', 'address_apartment', 'address_other']);
        });
    }
}
