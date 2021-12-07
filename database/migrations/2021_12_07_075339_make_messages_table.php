<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string("text");
            $table->foreignId('user_id');
            $table->foreignId("channel_id");
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign("channel_id")->references("id")->on("channels");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("messages");
    }
}
