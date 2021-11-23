<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('starter_dish_id')->constrained()->references('id')->on('dishes');
            $table->foreignId('main_dish_id')->constrained()->references('id')->on('dishes');
            $table->foreignId('second_dish_id')->nullable()->references('id')->on('dishes');
            $table->foreignId('dessert_id')->constrained()->references('id')->on('dishes');
            $table->dateTime('served_at')->unique()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
