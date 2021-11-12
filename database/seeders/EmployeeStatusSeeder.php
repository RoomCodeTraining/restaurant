<?php

namespace Database\Seeders;

use App\Models\EmployeeStatus;
use Illuminate\Database\Seeder;

class EmployeeStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmployeeStatus::create(['name' => 'Cadre']);
        EmployeeStatus::create(['name' => 'Maitrise']);
        EmployeeStatus::create(['name' => 'Stagiaire']);
    }
}
