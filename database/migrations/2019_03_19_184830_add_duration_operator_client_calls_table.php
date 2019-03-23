<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDurationOperatorClientCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_calls', function (Blueprint $table) {
            $table->string('hash')->nullable();
            $table->string('operator_text')->nullable();
            $table->integer('operator_id')->unsigned()->nullable();
            $table->foreign('operator_id')->references('id')->on('operators')->onDelete('set null');
            $table->integer('call_create_time')->nullable();
            $table->integer('call_end_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_calls', function (Blueprint $table) {
            $table->dropColumn(['operator_text', 'operator_id', 'hash', 'call_create_time', 'call_end_time']);
        });
    }
}
