<?php

namespace App\Console\Commands;

use App\Models\Department;
use App\Models\EmployeeStatus;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Création des utilisateurs de base';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = 'adminf@ciprel.com';
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

        $email = 'admin-technique@ciprel.com';
        $username = explode('@', $email)[0];

        User::create([
            'username' => $username,
            'identifier' => Str::upper(Str::random(5)),
            'first_name' => 'Admin',
            'last_name' => 'Technique',
            'user_type_id' => UserType::firstWhere('name', 'like', '%Agent CIPREL%')->id,
            'is_active' => true,
            'contact' => '+225 4845754864',
            'email' => $email,
            'email_verified_at' => now(),
            'department_id' => Department::first()->id,
            'organization_id' => Organization::first()->id,
            'employee_status_id' => EmployeeStatus::first()->id,
            'current_role_id' => Role::ADMIN_TECHNICAL,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ])->assignRole(Role::ADMIN_TECHNICAL);

        $email = 'admin-tech@ciprel.com';
        $username = explode('@', $email)[0];

        User::create([
            'username' => $username,
            'identifier' => Str::upper(Str::random(5)),
            'first_name' => 'Admin',
            'last_name' => 'Tech',
            'user_type_id' => UserType::firstWhere('name', 'like', '%Agent CIPREL%')->id,
            'is_active' => true,
            'contact' => '+225 4845754864',
            'email' => $email,
            'email_verified_at' => now(),
            'department_id' => Department::first()->id,
            'organization_id' => Organization::first()->id,
            'employee_status_id' => EmployeeStatus::first()->id,
            'current_role_id' => Role::ADMIN_TECHNICAL,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ])->assignRole(Role::ADMIN_TECHNICAL);

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
            'password_changed_at' => now()->subDays(120)
        ])->assignRole(Role::USER);

        $users = User::all()->filter(fn ($user) => ! $user->isFromLunchroom());

        foreach ($users as $key => $user) {
            $accessCard = $user->accessCards()->create([
                'identifier' => 'CARD00' . $key,
                'quota_breakfast' => config('cantine.quota_breakfast'),
                'quota_lunch' => config('cantine.quota_lunch'),
                'payment_method_id' => 1,
            ]);

            $accessCard->histories()->create([
                'user_id' => $user->id,
                'type' => 'primary',
                'attached_at' => now(),
            ]);

            $user->update(['current_access_card_id' => $accessCard->id]);
        }

        return Command::SUCCESS;

    }
}
