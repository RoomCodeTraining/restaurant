<?php

use App\Models\AccessCard;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReloadAccessCardHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reload_access_card_histories', function (Blueprint $table) {
            $table->id();
            $table->string('quota_type');
            $table->integer('quota');
            $table->foreignIdFor(AccessCard::class)->constrained();
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
        Schema::dropIfExists('reload_access_card_histories');
    }
}