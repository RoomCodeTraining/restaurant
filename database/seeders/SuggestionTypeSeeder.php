<?php

namespace Database\Seeders;

use App\Models\SuggestionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuggestionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SuggestionType::create([
            'id' => SuggestionType::IMPROVEMENT_APPLICATION,
            'name' => 'Amélioration de l\'application',
            'slug' => 'amelioration-de-l-application',
            'description' => 'Amélioration de l\'application',
        ]);

        SuggestionType::create([
            'id' => SuggestionType::IMPROVEMENT_CANTEEN_SERVICE,
            'name' => 'Amélioration du service de la cantine',
            'slug' => 'amelioration-du-service-de-la-cantine',
            'description' => 'Amélioration du service de la cantine',
        ]);
    }
}
