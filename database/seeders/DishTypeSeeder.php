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
        DB::table('dish_types')->insert([
            ['id' => DishType::DESSERT, 'name' => 'Dessert'],
            ['id' => DishType::STARTER, 'name' => 'Entrée'],
            ['id' => DishType::MAIN, 'name' => 'Plat principal']
        ]);

        if (app()->environment('local')) {
            Dish::factory()
                ->count(10)
                ->sequence(fn ($sequence) => ['dish_type_id' => DishType::all()->random()->id, 'name' => 'Plat '.$sequence->count])
                ->create();
        }
    }
}
