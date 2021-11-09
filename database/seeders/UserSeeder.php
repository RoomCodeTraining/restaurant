<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\EmployeeStatus;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super_admin = User::create([
            'username' => 'admin',
            'identifier' => 'DA001',
            'first_name' => 'Da',
            'last_name' => "Sié Roger",
            'is_external' => true,
            'is_active' => true,
            'email' => 'super-admin@ciprel.com',
            'email_verified_at' => now(),
            'employee_status_id' => 1,
            'remember_token' => Str::random(10),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password

        ]);
        $super_admin->assignRole(Role::ADMIN);
        $admin_rh = User::create(
            [
                'username' => 'admin-rh',
                'identifier' => 'KO001',
                'first_name' => 'Konan',
                'last_name' => "Alphonse",
                'is_external' => true,
                'is_active' => true,
                'email' => 'konan@ciprel.com',
                'email_verified_at' => now(),
                'organization_id' => 1,
                'employee_status_id' => 1,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
            ]
        );
        $admin_rh->assignRole(Role::ADMIN_RH);
        $cantine_lunchroom = User::create(
            [
                'username' => 'lunchroom',
                'identifier' => 'KO001',
                'first_name' => 'Koné',
                'last_name' => "Salimata",
                'is_external' => true,
                'is_active' => true,
                'email' => 'admin-lunchroom@ciprel.com',
                'email_verified_at' => now(),
                'employee_status_id' => 5,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
            ]
        );
        $cantine_lunchroom->assignRole(Role::ADMIN_LUNCHROOM);
        $cantine_admin = User::create(
            [
                'username' => 'ruth',
                'identifier' => 'SI001',
                'first_name' => 'Silué',
                'last_name' => "Ruth",
                'is_external' => true,
                'is_active' => true,
                'email' => 'ruth@ciprel.com',
                'organization_id' => 1,
                'department_id' => 2,
                'email_verified_at' => now(),
                'employee_status_id' => 4,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
            ]
        );

        $admin_accountant = User::create([
            'username' => 'admin-accountant',
            'identifier' => 'KO002',
            'first_name' => 'Kouassi',
            'last_name' => "Marceline",
            'is_external' => true,
            'is_active' => true,
            'email' => 'admin-accountant@ciprel.com',
            'organization_id' => 1,
            'department_id' => 2,
            'employee_status_id' => 4,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),

        ]);
        $admin_accountant->assignRole(Role::ACCOUNTANT);
    }
}
