<?php

namespace Database\Seeders;

use App\Models\SuggestionType;
use Illuminate\Database\Seeder;

class SuggestionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SuggestionType::create([
            'id' => \App\Enums\SuggestionType::IMPROVEMENT_APPLICATION,
            'name' => 'Amélioration de l\'application',
            'slug' => 'amelioration-application',
        ]);

        SuggestionType::create([
            'id' => \App\Enums\SuggestionType::BUG_REPORT,
            'name' => 'Signaler un bug',
            'slug' => 'signaler-un-bug',
        ]);

        SuggestionType::create([
            'id' => \App\Enums\SuggestionType::IMPROVEMENT_DISH,
            'name' => 'Amélioration d\'un plat',
            'slug' => 'amelioration-d-un-plat',
        ]);

        SuggestionType::create([
            'id' => \App\Enums\SuggestionType::IMPROVEMENT_MENU,
            'name' => 'Amélioration d\'un menu',
            'slug' => 'amelioration-d-un-menu',
        ]);

        SuggestionType::create([
            'id' => \App\Enums\SuggestionType::IMPROVEMENT_DELIVERY,
            'name' => 'Amélioration de la livraison',
            'slug' => 'amelioration-de-la-livraison',
        ]);

        SuggestionType::create([
            'id' => \App\Enums\SuggestionType::OTHER,
            'name' => 'Autre',
            'slug' => 'autre',
        ]);
    }
}