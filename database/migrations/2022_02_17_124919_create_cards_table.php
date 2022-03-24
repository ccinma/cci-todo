<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string("name", 255);
            $table->text("description")->nullable();
            $table->string('lane_id');
            $table->foreign('lane_id')->references('id')->on('lanes')->onDelete("cascade");
            $table->string('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete("cascade");
            $table->string('previous_id')->nullable();
            $table->string('next_id')->nullable();
            $table->timestamps();
        });

        Schema::table('cards', function (Blueprint $table) {
            $table->foreign('previous_id')->references('id')->on('cards');
            $table->foreign('next_id')->references('id')->on('cards');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards');
    }
}
