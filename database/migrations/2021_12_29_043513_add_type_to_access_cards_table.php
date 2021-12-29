<?php

use App\Models\AccessCard;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToAccessCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('access_cards', function (Blueprint $table) {
            $table->enum('type', [AccessCard::TYPE_PRIMARY, AccessCard::TYPE_TEMPORARY])->default(AccessCard::TYPE_PRIMARY);
            $table->timestamp('expires_at')->nullable();

            $table->index([ 'type', 'expires_at', ]);
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
