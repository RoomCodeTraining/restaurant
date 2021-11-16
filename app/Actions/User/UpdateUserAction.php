<?php

namespace App\Actions\User;

use App\Models\User;
use App\Events\UserUpdated;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UpdateUserAction
{
    public function execute(User $user, array $data)
    {
        DB::beginTransaction();

        $user->update([
            // 'identifier' => $data['identifier'],
            'username' => explode('@', $data['email'])[0],
            'email' => $data['email'],
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'contact' => $data['contact'],
            'employee_status_id' => (int) $data['employee_status_id'],
            'organization_id' => (int) $data['organization_id'],
            'department_id' => (int) $data['department_id'],
            'current_role_id' => $data['roles'][0] ?? Role::USER,
            'user_type' => $data['user_type'],
        ]);

        $user->syncRoles($data['roles'] ?? [Role::USER]);

        DB::commit();

        UserUpdated::dispatch($user);

        return $user->fresh();
    }
}
