<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCascadeToDishMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dish_menu', function (Blueprint $table) {
            $table->dropForeign('dish_menu_menu_id_foreign');
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }
}
