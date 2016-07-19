<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTootCardsTable extends Migration
{
    public function up()
    {
        Schema::create('toot_cards', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->integer('pin_code');
            $table->float('load');
            $table->float('points');
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('toot_cards');
    }
}
