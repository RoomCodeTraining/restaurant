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
            'served_at' => $this->faker->unique()->dateTimeBetween('-1 week', '+2 week'),
        ];
    }
}
