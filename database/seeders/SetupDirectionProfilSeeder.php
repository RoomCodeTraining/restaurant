<?php

namespace Database\Seeders;

use App\Models\Permission;
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
        $role = Role::create([
            'id' => Role::DATA_ANALYST,
            'name' => 'Top Manager',
        ])
        ->givePermissionTo([OrderPolicy::ORDER_MANAGE, SuggestionBoxPolicy::SUGGESTION_MANAGE]);
        $data = Permission::create([
            'name' => 'data.*',
            'description' => 'Toutes les permissions sur la vue data',
         ]);

        $data->children()->saveMany([
           new Permission([
             'name' => 'data.view',
             'description' => "Consulter la vue data",
           ]),
        ]);


        $role->givePermissionTo(['data.*', 'data.view']);

    }
}
