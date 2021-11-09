<?php

namespace Database\Seeders;

use App\Models\DishType;
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
            ['id' => DishType::DESSERT, 'name' => 'Dessert'],
            ['id' => DishType::STARTER, 'name' => 'Entrée'],
            ['id' => DishType::MAIN, 'name' => 'Plat principal']
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
