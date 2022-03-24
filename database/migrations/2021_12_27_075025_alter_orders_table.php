<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    if (DB::getDriverName() !== 'sqlite') {
      Schema::table('orders', function (Blueprint $table) {
        $table->dropForeign('orders_dish_id_foreign');
        $table->dropForeign('orders_menu_id_foreign');
        $table->foreignId('dish_id')->nullable()->change();
        $table->foreignId('menu_id')->nullable()->change();
        $table->foreignId('payment_method_id')->nullable();
        $table->enum('type', ['lunch', 'breakfast'])->default('lunch');
        $table->index(['dish_id', 'menu_id', 'type']);
      });
    }
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('orders', function (Blueprint $table) {
      //
    });
  }
}
