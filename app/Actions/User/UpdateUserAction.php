<?php

namespace App\Actions\User;

use App\Models\User;
use App\Events\UserUpdated;
use Illuminate\Support\Facades\DB;

class UpdateUserAction
{
    public function execute(User $user, array $data)
    {
        DB::beginTransaction();

        $user->update([
            'identifier' => $data['identifier'],
            'username' => $data['username'],
            'email' => $data['email'],
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'contact' => $data['contact'],
            'employee_status_id' => $data['employee_status_id'],
            'organization_id' => $data['organization_id'],
            'department_id' => $data['department_id'],
        ]);

        $user->syncRoles($data['roles'] ?? []);
        $user->syncPermissions($data['permissions'] ?? []);

        DB::commit();

        UserUpdated::dispatch($user);

        return $user->fresh();
    }
}
