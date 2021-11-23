<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\DishType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DishTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DishType::create(['id' => DishType::STARTER, 'name' => 'Entrée']);
        DishType::create(['id' => DishType::MAIN, 'name' => 'Plat principal']);
        DishType::create(['id' => DishType::DESSERT, 'name' => 'Dessert']);

        if (app()->environment('production')) {
            return;
        }

        Dish::factory()
            ->count(10)
            ->for(DishType::all()->random())
            ->sequence(fn ($sequence) => ['name' => 'Plat '.$sequence->index + 1])
            ->create();
    }
}
