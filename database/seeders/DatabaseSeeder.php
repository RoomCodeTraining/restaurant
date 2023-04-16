<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /*
          OrganizationSeeder::class,
            DepartmentSeeder::class,
            EmployeeStatusSeeder::class,
            PaymentMethodSeeder::class,
            UserTypeSeeder::class,
            PermissionSeeder::class,
            DishTypeSeeder::class,
            MenuSeeder::class,
            UserSeeder::class,
            */
        $this->call([
            SuggestionTypeSeeder::class,
        ]);
    }
}
