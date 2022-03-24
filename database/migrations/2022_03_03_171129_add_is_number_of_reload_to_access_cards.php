<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsNumberOfReloadToAccessCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('access_cards', function (Blueprint $table) {
            $table->integer('lunch_reload_count')->default(0);
            $table->integer('breakfast_reload_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('access_cards', function (Blueprint $table) {
            //
        });
    }
}
