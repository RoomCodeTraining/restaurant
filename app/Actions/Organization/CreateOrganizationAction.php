<?php

namespace App\Actions\Organization;

use App\Models\Dish;
use App\Events\UserCreated;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class CreateOrganizationAction
{
    public function execute(array $data): Organization
    {
        DB::beginTransaction();

        $dish = Organization::create([
            'name' => $data['name'],
        ]);

        DB::commit();
        return $dish;
    }
}
