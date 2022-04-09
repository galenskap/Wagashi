<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->foreign('lobby_owner')->references('id')->on('players')->onDelete('cascade');
            $table->foreign('current_dealer')->references('id')->on('players')->onDelete('cascade');
            $table->foreign('current_question')->references('id')->on('questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropForeign('lobby_owner');
            $table->dropForeign('current_dealer');
            $table->dropForeign('current_question');
        });
    }
}
