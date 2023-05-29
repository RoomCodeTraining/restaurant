<?php

namespace App\Actions\Organization;

use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class UpdateOrganizationAction
{
    public function execute(Organization $organization, array $data)
    {
        DB::beginTransaction();

        $organization->update([
             'name' => $data['name'],
            'family' => $data['family'],
            'is_entitled_two_dishes' => $data['is_entitled_two_dishes'],
            'description' => $data['description'],
        ]);



        DB::commit();

        return $organization->fresh();
    }
}
