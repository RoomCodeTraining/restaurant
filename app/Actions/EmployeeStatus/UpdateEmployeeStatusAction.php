<?php

namespace App\Actions\EmployeeStatus;

use App\Models\EmployeeStatus;

class UpdateEmployeeStatusAction
{
    public function execute(EmployeeStatus $employeeStatus, array $data): EmployeeStatus
    {
        $employeeStatus->update([
            'name' => $data['name'],
        ]);

        return $employeeStatus->fresh();
    }
}
