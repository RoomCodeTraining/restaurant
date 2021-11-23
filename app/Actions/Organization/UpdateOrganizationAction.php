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
        ]);

      

        DB::commit();

        return $organization->fresh();
    }
}
