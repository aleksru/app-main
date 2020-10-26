<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToRealizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('realizations', function (Blueprint $table) {
            $table->integer('realization_status_id')->unsigned()->nullable();
            $table->foreign('realization_status_id')->references('id')->on('other_statuses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('realizations', function (Blueprint $table) {
            $table->dropColumn('realization_status_id');
        });
    }
}
