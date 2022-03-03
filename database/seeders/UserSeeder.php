<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\EmployeeStatus;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $email = 'admin@ciprel.com';
        $username = explode('@', $email)[0];

        User::create([
            'username' => $username,
            'identifier' => Str::upper(Str::random(5)),
            'first_name' => 'Fonctionnel',
            'last_name' => 'Admin',
            'user_type_id' => UserType::firstWhere('name', 'like', '%Agent CIPREL%')->id,
            'is_active' => true,
            'contact' => '+225 0102030405',
            'email' => $email,
            'email_verified_at' => now(),
            'department_id' => Department::first()->id,
            'organization_id' => Organization::first()->id,
            'employee_status_id' => EmployeeStatus::first()->id,
            'current_role_id' => Role::ADMIN,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ])->assignRole(Role::ADMIN);

        if (app()->environment('production')) {
            return;
        }

        $email = 'admin-rh@ciprel.com';
        $username = explode('@', $email)[0];

        User::create([
            'username' => $username,
            'identifier' => Str::upper(Str::random(5)),
            'first_name' => 'Admin',
            'last_name' => 'RH',
            'user_type_id' => UserType::firstWhere('name', 'like', '%Agent CIPREL%')->id,
            'is_active' => true,
            'contact' => '+225 4845754864',
            'email' => $email,
            'email_verified_at' => now(),
            'department_id' => Department::first()->id,
            'organization_id' => Organization::first()->id,
            'employee_status_id' => EmployeeStatus::first()->id,
            'current_role_id' => Role::ADMIN_RH,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ])->assignRole(Role::ADMIN_RH);

        $email = 'comptable@ciprel.com';
        $username = explode('@', $email)[0];

        User::create([
            'username' => $username,
            'identifier' => Str::upper(Str::random(5)),
            'first_name' => 'Comptable',
            'last_name' => 'Respo',
            'user_type_id' => UserType::firstWhere('name', 'like', '%Agent CIPREL%')->id,
            'is_active' => true,
            'contact' => '+225 4845754864',
            'email' => $email,
            'email_verified_at' => now(),
            'department_id' => Department::first()->id,
            'organization_id' => Organization::first()->id,
            'employee_status_id' => EmployeeStatus::first()->id,
            'current_role_id' => Role::ACCOUNTANT,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ])->assignRole(Role::ACCOUNTANT);

        $email = 'operateur-cantine@ciprel.com';
        $username = explode('@', $email)[0];

        User::create([
            'username' => $username,
            'identifier' => Str::upper(Str::random(5)),
            'first_name' => 'Operateur',
            'last_name' => 'Cantine',
            'user_type_id' => UserType::firstWhere('name', 'like', '%Agent CIPREL%')->id,
            'is_active' => true,
            'contact' => '+225 4845754864',
            'email' => $email,
            'email_verified_at' => now(),
            'department_id' => Department::first()->id,
            'organization_id' => Organization::first()->id,
            'employee_status_id' => EmployeeStatus::first()->id,
            'current_role_id' => Role::OPERATOR_LUNCHROOM,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ])->assignRole(Role::OPERATOR_LUNCHROOM);

        $email = 'admin-cantine@ciprel.com';
        $username = Str::slug(explode('@', $email)[0]);

        User::create([
            'username' => $username,
            'identifier' => Str::upper(Str::random(5)),
            'first_name' => 'Admin',
            'last_name' => 'Cantine',
            'user_type_id' => UserType::firstWhere('name', 'like', '%Agent CIPREL%')->id,
            'is_active' => true,
            'contact' => '+225 4845754864',
            'email' => $email,
            'email_verified_at' => now(),
            'department_id' => Department::first()->id,
            'organization_id' => Organization::first()->id,
            'employee_status_id' => EmployeeStatus::first()->id,
            'current_role_id' => Role::ADMIN_LUNCHROOM,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ])->assignRole(Role::ADMIN_LUNCHROOM);

        $email = 'utilisateur@ciprel.com';
        $username = explode('@', $email)[0];

        User::create([
            'username' => $username,
            'identifier' => Str::upper(Str::random(5)),
            'first_name' => 'Utilisateur',
            'last_name' => 'Base',
            'user_type_id' => UserType::firstWhere('name', 'like', '%Prestataire%')->id,
            'is_active' => true,
            'contact' => '+225 4845754864',
            'email' => $email,
            'email_verified_at' => now(),
            'department_id' => Department::first()->id,
            'organization_id' => Organization::first()->id,
            'employee_status_id' => EmployeeStatus::first()->id,
            'current_role_id' => Role::USER,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ])->assignRole(Role::USER);

        $users = User::all()->filter(fn ($user) => ! $user->isFromLunchroom());

        foreach ($users as $key => $user) {
            $accessCard = $user->accessCards()->create([
                'identifier' => 'CARD00' . $key,
                'quota_breakfast' => 10,
                'quota_lunch' => 10,
                'payment_method_id' => 1,
            ]);

            $user->update(['current_access_card_id' => $accessCard->id]);
        }
    }
}
