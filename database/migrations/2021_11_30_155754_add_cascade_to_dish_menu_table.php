<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCascadeToDishMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      if (DB::getDriverName() !== 'sqlite') {
          Schema::table('dish_menu', function (Blueprint $table) {
              $table->dropForeign('dish_menu_menu_id_foreign');
              $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
          });
      }
    }
}
