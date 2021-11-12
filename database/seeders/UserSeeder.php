<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Enums\UserTypes;
use App\Models\Department;
use Illuminate\Support\Str;
use App\Models\Organization;
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
        $email = 'admin@ciprel.com';
        $username = Str::slug(explode('@', $email)[0]);

        User::create([
            'username' => $username,
            'identifier' => Str::upper(Str::random(5)),
            'first_name' => 'Fonctionnel',
            'last_name' => 'Admin',
            'user_type' => UserTypes::CIPREL_AGENT,
            'is_active' => true,
            'contact' => '+225 0102030405',
            'email' => $email,
            'email_verified_at' => now(),
            'department_id' => Organization::first()->id,
            'organization_id' => Organization::first()->id,
            'employee_status_id' => EmployeeStatus::first()->id,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ])->assignRole(Role::ADMIN);

        if (app()->environment('local')) {
            $roles = Role::pluck('id');
            $users = User::factory()
                ->count(Role::count())
                ->for(Organization::all()->random())
                ->for(Department::all()->random())
                ->for(EmployeeStatus::all()->random())
                ->create();

            $users->each(function ($user, $idx) use ($roles) {
                $user->assignRole($roles[$idx]);
            });
        }
    }
}
