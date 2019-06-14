<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFailDeliveryDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fail_delivery_dates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('morph_id')->unsigned()->nullable();
            $table->string('morph_type');
            $table->date('date');
            $table->tinyInteger('stop')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fail_delivery_dates');
    }
}
