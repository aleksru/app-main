<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductTypeRealizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('realizations', function (Blueprint $table) {
            $table->enum('product_type', [
                \App\Enums\ProductType::TYPE_PRODUCT,
                \App\Enums\ProductType::TYPE_ACCESSORY,
                \App\Enums\ProductType::TYPE_SERVICE
            ])->default(\App\Enums\ProductType::TYPE_PRODUCT);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
