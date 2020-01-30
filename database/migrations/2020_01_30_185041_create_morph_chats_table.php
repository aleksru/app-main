<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMorphChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('morph_chats', function (Blueprint $table) {
            $table->integer('chat_id')->unsigned();
            $table->foreign('chat_id')
                ->references('id')
                ->on('chats')
                ->onDelete('cascade');
            $table->integer('morph_id')->unsigned();
            $table->string('morph_type');
            $table->primary(['chat_id', 'morph_id', 'morph_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('morph_chats');
    }
}
