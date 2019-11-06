<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusesOtherStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses_other_statuses', function (Blueprint $table) {
            $table->integer('order_status_id')->unsigned();
            $table->integer('other_status_id')->unsigned();
            $table->foreign('order_status_id')
                ->references('id')
                ->on('order_statuses')
                ->onDelete('cascade');
            $table->foreign('other_status_id')
                ->references('id')
                ->on('other_statuses')
                ->onDelete('cascade');
            $table->primary(['order_status_id', 'other_status_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statuses_other_statuses');
    }
}
