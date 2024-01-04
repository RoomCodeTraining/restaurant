<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Organization::create([
            'name' => 'Restaurant Emmannuel',
        ]);

        $this->call([
            SuggestionTypeSeeder::class,
            DishSeeder::class,
            UserSeeder::class,
        ]);
    }
}