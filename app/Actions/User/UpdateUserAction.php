<?php

namespace App\Actions\User;

use App\Events\UserUpdated;
use App\Models\User;
use App\Support\ActivityHelper;
use Illuminate\Support\Facades\DB;

class UpdateUserAction
{
    public function execute(User $user, array $data)
    {
        DB::beginTransaction();
        $user->update([
            // 'identifier' => $data['identifier'],
            'identifier' => $data['identifier'],
            'username' => explode('@', $data['email'])[0],
            'email' => $data['email'],
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'contact' => $data['contact'],
            'employee_status_id' => (int) $data['employee_status_id'],
            'organization_id' => (int) $data['organization_id'],
            'department_id' => (int) $data['department_id'],
            'current_role_id' => (int) $data['current_role_id'],
            'user_type_id' => $data['user_type_id'],
            'is_entitled_breakfast' => $data['is_entitled_breakfast'],
        ]);

        $user->syncRoles((int) $data['current_role_id']);


        ActivityHelper::createActivity($user, "Le profil de {$user->full_name} a été mis à jour par {$user->full_name}", 'Mise à jour du profil');


        DB::commit();

        UserUpdated::dispatch($user);

        return $user->fresh();
    }
}
