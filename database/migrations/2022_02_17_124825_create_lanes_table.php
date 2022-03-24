<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lanes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string("name", 25);
            $table->string('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete("cascade");
            $table->string('board_id');
            $table->foreign('board_id')->references('id')->on('boards')->onDelete("cascade");
            $table->string('previous_id')->nullable();
            $table->string('next_id')->nullable();
            $table->timestamps();
        });

        Schema::table('lanes', function (Blueprint $table) {
            $table->foreign('previous_id')->references('id')->on('lanes');
            $table->foreign('next_id')->references('id')->on('lanes');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lanes');
    }
}
