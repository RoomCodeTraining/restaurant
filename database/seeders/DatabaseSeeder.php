<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\MenuSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\DishTypeSeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\OrganizationSeeder;
use Database\Seeders\EmployeeStatusSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            OrganizationSeeder::class,
            DepartmentSeeder::class,
            EmployeeStatusSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            DishTypeSeeder::class,
            MenuSeeder::class
        ]);
    }
}
