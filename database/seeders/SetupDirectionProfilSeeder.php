<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Policies\OrderPolicy;
use App\Policies\SuggestionBoxPolicy;
use Illuminate\Database\Seeder;

class SetupDirectionProfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'id' => Role::DATA_ANALYST,
            'name' => 'Top Manager',
        ])
        ->givePermissionTo([OrderPolicy::ORDER_MANAGE, SuggestionBoxPolicy::SUGGESTION_MANAGE]);


    }
}