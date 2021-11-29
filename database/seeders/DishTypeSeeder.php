<?php

namespace Database\Seeders;

use App\Models\DishType;
use Illuminate\Database\Seeder;

class DishTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DishType::create(['id' => DishType::STARTER, 'name' => 'Entrée', 'is_orderable' => false]);
        DishType::create(['id' => DishType::MAIN, 'name' => 'Plat principal', 'is_orderable' => true]);
        DishType::create(['id' => DishType::DESSERT, 'name' => 'Déssert', 'is_orderable' => false]);
    }
}
