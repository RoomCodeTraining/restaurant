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
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'user_type' => $this->faker->randomElement(array_keys(UserTypes::getUserTypes())),
            'is_active' => $this->faker->randomElement([0, 1]),
            'contact' => $this->faker->phoneNumber,
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
