<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('substatus_id')->unsigned()->nullable()->after('status_id');
            $table->foreign('substatus_id')->references('id')->on('other_statuses')->onDelete('set null');
            $table->integer('stock_status_id')->unsigned()->nullable()->after('substatus_id');
            $table->foreign('stock_status_id')->references('id')->on('other_statuses')->onDelete('set null');
            $table->integer('logistic_status_id')->unsigned()->nullable()->after('stock_status_id');
            $table->foreign('logistic_status_id')->references('id')->on('other_statuses')->onDelete('set null');
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
            $table->dropColumn(['substatus_id', 'stock_status_id', 'logistic_status_id']);
        });
    }
}
