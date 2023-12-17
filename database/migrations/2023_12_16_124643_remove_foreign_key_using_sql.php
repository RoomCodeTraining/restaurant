<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE access_cards DROP FOREIGN KEY access_cards_user_id_foreign');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};