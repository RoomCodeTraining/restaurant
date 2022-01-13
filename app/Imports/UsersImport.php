<?php

namespace App\Imports;

use App\Events\UserCreated;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        DB::beginTransaction();
       //$employee_status_id = \App\Models\EmployeeStatus::where('name', $row['categorie'])->first()->id;
        $user = User::create([
            'identifier' => $row['matricule'],
            'username' => explode('@', $row['email'])[0],
            'email' => $row['email'],
            'last_name' => $row['nom'],
            'first_name' => $row['prenoms'],
            'contact' => $row['contact'],
            'current_role_id' => Role::getRole($row['profil']) ?? Role::USER,
            'employee_status_id' => 1,
            'department_id' => \App\Models\Department::where('name', $row['departement'])->first()->id,
            'organization_id' => \App\Models\Organization::where('name', $row['societe'])->first()->id,
            'user_type_id' => \App\Models\UserType::where('name', $row['type_de_collaborateur'])->first()->id,
            'email_verified_at' => now(),
        ]);

        $user->syncRoles(Role::getRole($row['profil']) ?? [Role::USER]);

        DB::commit();

        UserCreated::dispatch($user);

        //$user->sendWelcomeNotification(now()->addWeek());
    }
}
