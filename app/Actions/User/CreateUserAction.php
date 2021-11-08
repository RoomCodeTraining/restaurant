<?php

namespace App\Actions\User;

use App\Models\Role;
use App\Models\User;
use App\Events\UserCreated;
use Illuminate\Support\Facades\DB;

class CreateUserAction
{
    public function execute(array $data): User
    {
        DB::beginTransaction();

        $user = User::create([
            'identifier' => $data['identifier'],
            'username' => $data['username'],
            'email' => $data['email'],
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'contact' => $data['contact'],
            'employee_status_id' => $data['employee_status_id'],
            'organization_id' => $data['organization_id'],
            'department_id' => $data['department_id'],
            'email_verified_at' => now(),
        ]);

        $user->syncRoles($data['roles'] ?? [Role::USER]);
        $user->syncPermissions($data['permissions'] ?? []);

        DB::commit();

        UserCreated::dispatch($user);

        // $user->sendWelcomeNotification(now()->addWeek());

        return $user;
    }
}
