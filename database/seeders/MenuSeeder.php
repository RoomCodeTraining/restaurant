<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i <= 30; $i++) {
            Menu::create([
                'starter_dish_id' =>  Dish::all()->random()->id,
                'main_dish_id' =>  Dish::all()->random()->id,
                'second_dish_id' =>  Dish::all()->random()->id,
                'dessert_id' =>  Dish::all()->random()->id,
                'served_at' => now()->addDays($i)
            ]);
        }
    }
}
