<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'id' => \App\Enums\Role::ADMIN,
            'name' => 'Administrateur du restaurant',
        ]);

        Role::create([
            'id' => \App\Enums\Role::USER,
            'name' => 'Client du restaurant',
        ]);

        User::create([
            'identifier' => 'admin',
            'first_name' => 'Emmanuel',
            'last_name' => 'Restaurant',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => \App\Enums\Role::ADMIN,
        ]);


    }
}