<?php

namespace App\Actions\Organization;

use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class CreateOrganizationAction
{
    public function execute(array $data): Organization
    {
        DB::beginTransaction();

        $dish = Organization::create([
            'name' => $data['name'],
            'family' => $data['family'],
            'is_entitled_two_dishes' => $data['is_entitled_two_dishes'],
            'description' => $data['description'],
        ]);

        DB::commit();

        return $dish;
    }
}
