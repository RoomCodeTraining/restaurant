<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Department::create(['name' => 'Informatique']);
        Department::create(['name' => 'Comptabilité']);

        if (app()->environment('local')) {
            Department::factory()->count(10)->create();
        }
    }
}
