<?php

namespace Database\Seeders;

use Faker\Provider\Lorem;
use Illuminate\Database\Seeder;

class DishTypesAndDishesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dishTypes = [
            ['name' => 'Dessert'],
            ['name' => 'Entrée'],
            ['name' => 'Plat pricipal']
        ];


        foreach ($dishTypes as $dishType) {
            \App\Models\DishType::create($dishType);
        }

        for ($i = 0; $i < 15; $i++) {
            $dish = \App\Models\Dish::create([
                'name' => "Plat $i",
                'description' => "Desription du plat",
                'dish_type_id' => rand(1, 3)
            ]);
        }
    }
}
