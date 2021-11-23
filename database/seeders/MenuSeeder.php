<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\DishType;
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
        if (app()->environment('production')) {
            return;
        }

        Menu::factory()
            ->count(20)
            ->sequence(fn ($sequence) => [
                'starter_dish_id' => Dish::where('dish_type_id', DishType::STARTER)->get()->random()->id,
                'main_dish_id' => Dish::where('dish_type_id', DishType::MAIN)->get()->random()->id,
                'second_dish_id' => Dish::where('dish_type_id', DishType::MAIN)->get()->random()->id,
                'dessert_id' => Dish::where('dish_type_id', DishType::DESSERT)->get()->random()->id,
            ])
            ->create();
    }
}
