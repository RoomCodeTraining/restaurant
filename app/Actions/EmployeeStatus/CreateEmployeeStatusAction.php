<?php

namespace App\Actions\EmployeeStatus;

use App\Models\EmployeeStatus;

class CreateEmployeeStatusAction
{
    public function execute(array $data): EmployeeStatus
    {
        $employeeStatus = EmployeeStatus::create([
            'name' => $data['name'],
        ]);

        return $employeeStatus;
    }
}
