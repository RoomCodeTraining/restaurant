<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\DishType;
use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Sequence;
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

        $faker = \Faker\Factory::create();

        Menu::factory()
            ->count(5)
            ->hasAttached(
                Dish::factory()
                    ->count(4)
                    ->state(new Sequence(
                        [ 'name' => $faker->sentence(2), 'dish_type_id' => DishType::STARTER ],
                        [ 'name' => $faker->sentence(3), 'dish_type_id' => DishType::MAIN ],
                        [ 'name' => $faker->sentence(3), 'dish_type_id' => DishType::MAIN ],
                        [ 'name' => $faker->sentence(2), 'dish_type_id' => DishType::DESSERT ],
                    ))
            )
            ->create();
    }
}
