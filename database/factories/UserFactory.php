<?php

namespace Database\Factories;

use App\Enums\UserTypes;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $email = $this->faker->unique()->safeEmail;
        $username = explode('@', $email)[0];

        return [
            'username' => $username,
            'identifier' => Str::upper(Str::random(5)),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'is_active' => $this->faker->randomElement([0, 1]),
            'contact' => $this->faker->phoneNumber,
            'email' => $email,
            'email_verified_at' => now(),
            'user_type_id' => null,
            'department_id' => null,
            'organization_id' => null,
            'employee_status_id' => null,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
