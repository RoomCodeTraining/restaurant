<?php

namespace App\Actions\Department;

use App\Models\Department;
use Illuminate\Support\Facades\DB;

class CreateDepartmentAction
{
    public function execute(array $data): Department
    {
        DB::beginTransaction();

        $menu = Department::create([
            'name' => $data['name'],
        ]);

        DB::commit();

        return $menu;
    }
}
