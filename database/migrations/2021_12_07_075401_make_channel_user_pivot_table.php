<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeChannelUserPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("channel_user", function(Blueprint $table){
            $table->id();
            $table->foreignId('channel_id');
            $table->foreignId('user_id');
            $table->timestamps();

            $table->foreign("channel_id")->references("id")->on("channels");
            $table->foreign("user_id")->references("id")->on("users");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("channel_user");
    }
}
