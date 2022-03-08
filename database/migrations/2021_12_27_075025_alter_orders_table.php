<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_dish_id_foreign');
            $table->dropForeign('orders_menu_id_foreign');
            $table->foreignId('dish_id')->nullable()->change();
            $table->foreignId('menu_id')->nullable()->change();
            $table->foreignId('payment_method_id')->nullable();
            $table->enum('type', ['lunch', 'breakfast'])->default('lunch');
            $table->boolean('is_exceptional')->default(false);

            $table->index(['dish_id', 'menu_id', 'type']);
        });
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
