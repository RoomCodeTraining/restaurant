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
        $username = Str::slug(explode('@', $email)[0]);

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

        $roles = Role::pluck('id');
        $users = User::factory()
                ->count(Role::count())
                ->state(['current_role_id' => Role::USER, 'is_active' => true])
                ->for(Organization::first())
                ->for(Department::all()->random())
                ->for(EmployeeStatus::all()->random())
                ->for(UserType::firstWhere('name', 'like', '%Agent CIPREL%'))
                ->create();

        $users->each(function ($user, $idx) use ($roles) {
            $user->assignRole($roles[$idx]);
            $user->update(['current_role_id' => $roles[$idx]]);
        });
    }
}
