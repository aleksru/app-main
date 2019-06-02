<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPassportDataCouriersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('couriers', function (Blueprint $table) {
            $table->date('birth_day')->nullable();
            $table->string('passport_number')->nullable();
            $table->date('passport_date')->nullable();
            $table->string('passport_issued_by')->nullable();
            $table->string('passport_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('couriers', function (Blueprint $table) {
            $table->dropColumn([
                'birth_day',
                'passport_number',
                'passport_date',
                'passport_issued_by',
                'passport_address'
            ]);
        });
    }
}
