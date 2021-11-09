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
            'username' => Str::slug(explode('@', $data['email'])[0]),
            'email' => $data['email'],
            // 'password' => bcrypt(Str::random(8)),
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'contact' => $data['contact'],
            'employee_status_id' => (int) $data['employee_status_id'],
            'organization_id' => (int) $data['organization_id'],
            'department_id' => (int) $data['department_id'],
            'is_external' => (bool) $data['is_external'],
            'email_verified_at' => now(),
        ]);


        $user->syncRoles($data['roles'] ?? [Role::USER]);
        $user->syncPermissions($data['permissions'] ?? []);

        DB::commit();

        UserCreated::dispatch($user);

        $user->sendWelcomeNotification(now()->addWeek());

        return $user;
    }
}
