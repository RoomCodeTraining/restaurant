<?php

namespace App\Imports;

use App\Models\Role;
use App\Models\User;
use App\Events\UserCreated;
use App\Models\Department;
use App\Models\EmployeeStatus;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
  use Importable;

  /**
   * @param array $row
   *
   * @return \Illuminate\Database\Eloquent\Model|null
   */
  public function model(array $row)
  {

    //Informatique
    DB::beginTransaction();

    $data = $this->getUserhasBeingCreatedData($row);
    $user = User::updateOrCreate([
      'identifier' => $data['identifier'],
    ], $data);
    $user->syncRoles(Role::getRole(strtolower($row['profil'])) ?? [Role::USER]);

    DB::commit();

    UserCreated::dispatch($user);
    session()->flash('success', 'Les utilisateurs ont été importés!');



    $user->sendWelcomeNotification(now()->addWeek());
  }


  public function rules(): array
  {
    return [
      'matricule' => 'required',
      'prenoms' => 'nullable|string',
      'nom' => 'required|string',
      'societe' => 'required|string',
      'email' => ['required', 'email'],
      'categorie' =>  'required|string',
      'departement' => ['required', 'string', Rule::exists('departments', 'name')],
      'profil' => 'required|string',
      'type' => 'required'
    ];
  }


  public function getUserhasBeingCreatedData($model): array
  {

    $categorie = trim(str_replace('î', 'i', $model['categorie']));

    return [
      'employee_status_id' => \App\Models\EmployeeStatus::whereName(trim(ucfirst($categorie)))->first()->id,
      'organization_id' => \App\Models\Organization::whereName(trim(ucfirst($model['societe'])))->first()->id,
      'department_id' => \App\Models\Department::whereName(trim(ucfirst($model['departement'])))->first()->id,
      'user_type_id' => \App\Models\UserType::whereName(trim(ucfirst($model['type'])))->first()->id,
      'identifier' => $model['matricule'],
      'username' => explode('@', $model['email'])[0],
      'email' => $model['email'],
      'last_name' => $model['nom'],
      'first_name' => $model['prenoms'],
      'contact' => $model['contact'],
      'current_role_id' => Role::getRole(strtolower($model['profil'])) ?? Role::USER,
      'email_verified_at' => now(),
    ];
  }
}
