<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards_labels', function (Blueprint $table) {
            $table->unsignedBigInteger('card_id');
            $table->unsignedBigInteger('label_id');
            $table->primary(["card_id", "label_id"]);
            $table->foreign('card_id')->references('id')->on('cards')->onDelete("cascade");
            $table->foreign('label_id')->references('id')->on('labels')->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards_labels');
    }
}
