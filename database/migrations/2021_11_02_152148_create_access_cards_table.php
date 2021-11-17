<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_cards', function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->index();
            $table->unsignedInteger('quota_breakfast');
            $table->unsignedInteger('quota_lunch');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('payment_method_id')->constrained();
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
        Schema::dropIfExists('access_cards');
    }
}
