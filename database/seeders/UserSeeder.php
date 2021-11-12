<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\EmployeeStatus;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
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
        User::factory()
            ->state([ 'email' => 'admin@ciprel.com', 'username' => 'admin' ])
            ->for(Organization::first())
            ->for(Department::first())
            ->for(EmployeeStatus::first())
            ->create()
            ->assignRole(Role::ADMIN);

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
