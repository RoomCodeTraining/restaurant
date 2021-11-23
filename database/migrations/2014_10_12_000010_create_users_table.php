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
            $table->string('username');
            $table->string('profile_photo_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('contact')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->foreignId('user_type_id');
            $table->foreignId('employee_status_id')->constrained();
            $table->foreignId('department_id')->constrained()->nullable();
            $table->foreignId('organization_id')->constrained()->nullable();
            $table->foreignId('current_role_id');
            $table->foreignId('current_access_card_id')->nullable();
            $table->timestamp('password_changed_at')->nullable();
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
