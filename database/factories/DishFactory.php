<?php

namespace Database\Factories;

use App\Models\DishType;
use Illuminate\Database\Eloquent\Factories\Factory;

class DishFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => '',
            'description' => $this->faker->text,
            'image_path' => $this->faker->imageUrl(400, 300),
            'dish_type_id' => $this->faker->randomElement([DishType::DESSERT, DishType::MAIN, DishType::STARTER]),
        ];
    }
}
