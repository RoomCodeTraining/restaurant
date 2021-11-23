<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\DishType;
use Illuminate\Database\Eloquent\Factories\Sequence;
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
        DishType::create(['id' => DishType::STARTER, 'name' => 'Entrée']);
        DishType::create(['id' => DishType::MAIN, 'name' => 'Plat principal']);
        DishType::create(['id' => DishType::DESSERT, 'name' => 'Dessert']);

        if (app()->environment('production')) {
            return;
        }

        $faker = \Faker\Factory::create();

        Dish::factory()
            ->count(10)
            ->sequence(fn (Sequence $sequence) => [
                'name' => 'Plat ' . $sequence->index + 1,
                'dish_type_id' => $faker->randomElement([DishType::STARTER, DishType::MAIN, DishType::DESSERT]),
            ])
            ->create();
    }
}
