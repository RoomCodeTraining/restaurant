<?php

use App\Models\Dish;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_specal', function (Blueprint $table) {
            $table->id();
            $table->dateTime('served_at')->unique()->index();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignIdFor(Dish::class)->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('menu_specal');
    }
};
