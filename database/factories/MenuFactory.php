<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'starter_dish_id' => null,
            'main_dish_id' => null,
            'second_dish_id' => null,
            'dessert_id' => null,
            'served_at' => $this->faker->dateTimeBetween('+1 day', '+29 days'),
        ];
    }
}
