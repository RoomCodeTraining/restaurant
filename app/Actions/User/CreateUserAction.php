<?php

namespace App\Actions\User;

use App\Events\UserCreated;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use App\Support\ActivityHelper;
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
            'user_type_id' => $data['user_type_id'],
            'email_verified_at' => now(),
            'is_entitled_breakfast' => $data['is_entitled_breakfast'],
        ]);

        $organization = $user->organization;

        ActivityHelper::createActivity(
            $user,
            "Creation du compte de $user->full_name",
            'Creation de nouveau menu',
        );

        $user->syncRoles($data['roles'] ?? [Role::USER]);

        DB::commit();

        UserCreated::dispatch($user);

        // checkin pour s'assurer que l'utilisateur recoit le mail de bienvenue pour acceder a l'application
        if($organization->family === Organization::GROUP_1) {
            $user->sendWelcomeNotification(now()->addWeek());
        }

        return $user;
    }
}