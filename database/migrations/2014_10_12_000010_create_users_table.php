<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('identifier');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name')->virtualAs('concat(first_name, " ", last_name)');
            $table->string('profile_photo_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('contact')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->foreignId('organization_id')->nullable();
            $table->timestamp('password_changed_at')->nullable();
            $table->timestamp('subscription_starts_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}