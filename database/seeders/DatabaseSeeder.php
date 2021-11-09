<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\EmployeeStatus;
use Illuminate\Database\Seeder;
use Database\Seeders\MenuSeeder;
use Database\Seeders\DishTypesAndDishesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        foreach(EmployeeStatus::getAllStatuses() as $status) {
            EmployeeStatus::create(['name' => $status]);
        }

        $this->call([
            PermissionsAndRolesSeeder::class,
            DepartmentSeeder::class,
            OrganizationSeeder::class,
            DishTypesAndDishesSeeder::class,
            UserSeeder::class,
            MenuSeeder::class
        ]);
        

        for($i=0; $i<20; $i++) {
            $user = User::factory(1)->create();
        }
       
    }
}
