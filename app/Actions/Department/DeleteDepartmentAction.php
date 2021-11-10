<?php

namespace App\Actions\Department;

use App\Models\Menu;
use App\Models\User;
use App\Events\UserDeleted;
use App\Models\Department;
use Illuminate\Validation\ValidationException;

final class DeleteDepartmentAction
{
    public function execute($department): Department
    {
        if (null !== $department->deleted_at) {
            throw_if(null !== $department->deleted_at, ValidationException::withMessages([
                'delete_department' => 'Ce departement est deja supprimé.',
            ]));
        }

        $department->delete();

       // dishDeleted::dispatch($dish);

        return $department;
    }
}
