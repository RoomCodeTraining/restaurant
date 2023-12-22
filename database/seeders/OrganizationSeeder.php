<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Organization::create(['name' => 'Ciprel']);
        Organization::create(['name' => 'GS2E']);
        Organization::create(['name' => 'ATINKOU']);
        Organization::create(['name' => 'GENDARMERIE', 'is_entitled_two_dishes' => true]);
    }
}
