<?php

namespace Database\Seeders;

use App\Models\DishType;
use Illuminate\Database\Seeder;

class DishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DishType::create([
            'id' => \App\Enums\DishType::MAIN,
            'name' => 'Plat principal',
        ]);

        DishType::create([
            'id' => \App\Enums\DishType::SIDE,
            'name' => 'Accompagnement',
        ]);

        $dishes = [
            [
                'name' => 'Poulet rôti',
                'dish_type_id' => \App\Enums\DishType::MAIN,
            ],
            [
                'name' => 'Poulet frit',
                'dish_type_id' => \App\Enums\DishType::MAIN,
            ],
            [
                'name' => 'Poulet grillé',
                'dish_type_id' => \App\Enums\DishType::MAIN,
            ],
            [
                'name' => 'Carpe braisé',
                'dish_type_id' => \App\Enums\DishType::MAIN,
            ],
            [
                'name' => 'Carpe frit',
                'dish_type_id' => \App\Enums\DishType::MAIN,
            ],
            [
                'name' => 'Attieké',
                'dish_type_id' => \App\Enums\DishType::SIDE,
            ],
            [
                'name' => 'Foufou',
                'dish_type_id' => \App\Enums\DishType::SIDE,
            ],
            [
                'name' => 'Riz',
                'dish_type_id' => \App\Enums\DishType::SIDE,
            ],
        ];

        foreach ($dishes as $dish) {
            \App\Models\Dish::create($dish);
        }
    }
}