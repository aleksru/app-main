<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('set null');

            $table->decimal('price', 10, 2)->nullable();
            $table->integer('quantity')->nullable();
            $table->string('imei')->nullable();
            $table->decimal('price_opt', 10, 2)->nullable();
            $table->integer('supplier_id')->unsigned()->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
            $table->decimal('courier_payment', 10, 2)->nullable();
            $table->decimal('delta', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_product');
    }
}
