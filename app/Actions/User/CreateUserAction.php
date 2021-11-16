<?php

namespace App\Actions\User;

use App\Models\Role;
use App\Models\User;
use App\Events\UserCreated;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CreateUserAction
{
    public function execute(array $data): User
    {
        DB::beginTransaction();

        $user = User::create([
            'identifier' => $data['identifier'],
            'username' => explode('@', $data['email'])[0],
            'email' => $data['email'],
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'contact' => $data['contact'],
            'current_role_id' => $data['roles'][0] ?? Role::USER,
            'employee_status_id' => (int) $data['employee_status_id'],
            'organization_id' => (int) $data['organization_id'],
            'department_id' => (int) $data['department_id'],
            'user_type' => $data['user_type'],
            'email_verified_at' => now(),
        ]);

        $user->syncRoles($data['roles'] ?? [Role::USER]);

        $user->update(['current_role_id' => $user->roles()->first()->id]);

        DB::commit();

        UserCreated::dispatch($user);

        $user->sendWelcomeNotification(now()->addWeek());

        return $user;
    }
}
