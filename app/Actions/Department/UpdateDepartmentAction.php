<?php

namespace App\Actions\Department;

use App\Models\Department;
use Illuminate\Support\Facades\DB;

class UpdateDepartmentAction
{
    public function execute(Department $department, array $data)
    {
        DB::beginTransaction();

        $department->update([
            'name' => $data['name'],
        ]);

        DB::commit();

        return $department->fresh();
    }
}
