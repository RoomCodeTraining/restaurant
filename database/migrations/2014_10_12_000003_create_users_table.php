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
            $table->boolean('is_external')->default(false);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('contact')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->foreignId('department_id')->constrained();
            $table->foreignId('employee_status_id')->constrained();
            $table->foreignId('organization_id')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
