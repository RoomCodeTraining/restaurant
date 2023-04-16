<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\EmployeeStatus;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $email = 'admin@ciprel.com';
    $username = explode('@', $email)[0];

    User::create([
      'username' => $username,
      'identifier' => Str::upper(Str::random(5)),
      'first_name' => 'Fonctionnel',
      'last_name' => 'Admin',
      'user_type_id' => UserType::firstWhere('name', 'like', '%Agent CIPREL%')->id,
      'is_active' => true,
      'contact' => '+225 0102030405',
      'email' => $email,
      'email_verified_at' => now(),
      'department_id' => Department::first()->id,
      'organization_id' => Organization::first()->id,
      'employee_status_id' => EmployeeStatus::first()->id,
      'current_role_id' => Role::ADMIN,
      'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
      'remember_token' => Str::random(10),
    ])->assignRole(Role::ADMIN);

    $email = 'admin-tech@ciprel.com';
    $username = explode('@', $email)[0];

    User::create([
      'username' => $username,
      'identifier' => Str::upper(Str::random(5)),
      'first_name' => 'Admin',
      'last_name' => 'Tech',
      'user_type_id' => UserType::firstWhere('name', 'like', '%Agent CIPREL%')->id,
      'is_active' => true,
      'contact' => '+225 4845754864',
      'email' => $email,
      'email_verified_at' => now(),
      'department_id' => Department::first()->id,
      'organization_id' => Organization::first()->id,
      'employee_status_id' => EmployeeStatus::first()->id,
      'current_role_id' => Role::ADMIN_TECHNICAL,
      'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
      'remember_token' => Str::random(10),
    ])->assignRole(Role::ADMIN_TECHNICAL);

    $email = 'admin-rh@ciprel.com';
    $username = explode('@', $email)[0];

    User::create([
      'username' => $username,
      'identifier' => Str::upper(Str::random(5)),
      'first_name' => 'Admin',
      'last_name' => 'RH',
      'user_type_id' => UserType::firstWhere('name', 'like', '%Agent CIPREL%')->id,
      'is_active' => true,
      'contact' => '+225 4845754864',
      'email' => $email,
      'email_verified_at' => now(),
      'department_id' => Department::first()->id,
      'organization_id' => Organization::first()->id,
      'employee_status_id' => EmployeeStatus::first()->id,
      'current_role_id' => Role::ADMIN_RH,
      'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
      'remember_token' => Str::random(10),
    ])->assignRole(Role::ADMIN_RH);

    $email = 'comptable@ciprel.com';
    $username = explode('@', $email)[0];

    User::create([
      'username' => $username,
      'identifier' => Str::upper(Str::random(5)),
      'first_name' => 'Comptable',
      'last_name' => 'Respo',
      'user_type_id' => UserType::firstWhere('name', 'like', '%Agent CIPREL%')->id,
      'is_active' => true,
      'contact' => '+225 4845754864',
      'email' => $email,
      'email_verified_at' => now(),
      'department_id' => Department::first()->id,
      'organization_id' => Organization::first()->id,
      'employee_status_id' => EmployeeStatus::first()->id,
      'current_role_id' => Role::ACCOUNTANT,
      'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
      'remember_token' => Str::random(10),
    ])->assignRole(Role::ACCOUNTANT);

    $email = 'operateur-cantine@ciprel.com';
    $username = explode('@', $email)[0];

    User::create([
      'username' => $username,
      'identifier' => Str::upper(Str::random(5)),
      'first_name' => 'Operateur',
      'last_name' => 'Cantine',
      'user_type_id' => UserType::firstWhere('name', 'like', '%Agent CIPREL%')->id,
      'is_active' => true,
      'contact' => '+225 4845754864',
      'email' => $email,
      'email_verified_at' => now(),
      'department_id' => Department::first()->id,
      'organization_id' => Organization::first()->id,
      'employee_status_id' => EmployeeStatus::first()->id,
      'current_role_id' => Role::OPERATOR_LUNCHROOM,
      'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
      'remember_token' => Str::random(10),
    ])->assignRole(Role::OPERATOR_LUNCHROOM);

    $email = 'admin-cantine@ciprel.com';
    $username = Str::slug(explode('@', $email)[0]);

    User::create([
      'username' => $username,
      'identifier' => Str::upper(Str::random(5)),
      'first_name' => 'Admin',
      'last_name' => 'Cantine',
      'user_type_id' => UserType::firstWhere('name', 'like', '%Agent CIPREL%')->id,
      'is_active' => true,
      'contact' => '+225 4845754864',
      'email' => $email,
      'email_verified_at' => now(),
      'department_id' => Department::first()->id,
      'organization_id' => Organization::first()->id,
      'employee_status_id' => EmployeeStatus::first()->id,
      'current_role_id' => Role::ADMIN_LUNCHROOM,
      'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
      'remember_token' => Str::random(10),
    ])->assignRole(Role::ADMIN_LUNCHROOM);

    $email = 'utilisateur@ciprel.com';
    $username = explode('@', $email)[0];

    User::create([
      'username' => $username,
      'identifier' => Str::upper(Str::random(5)),
      'first_name' => 'Utilisateur',
      'last_name' => 'Base',
      'user_type_id' => UserType::firstWhere('name', 'like', '%Prestataire%')->id,
      'is_active' => true,
      'contact' => '+225 4845754864',
      'email' => $email,
      'email_verified_at' => now(),
      'department_id' => Department::first()->id,
      'organization_id' => Organization::first()->id,
      'employee_status_id' => EmployeeStatus::first()->id,
      'current_role_id' => Role::USER,
      'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
      'remember_token' => Str::random(10),
    ])->assignRole(Role::USER);

    $users = User::all()->filter(fn ($user) => !$user->isFromLunchroom());

    foreach ($users as $key => $user) {
      $accessCard = $user->accessCards()->create([
        'identifier' => 'CARD00' . $key,
        'quota_breakfast' => 0,
        'quota_lunch' => 0,
        'payment_method_id' => 1,
      ]);

      $user->update(['current_access_card_id' => $accessCard->id]);
    }

    if (app()->environment('production')) {
      return;
    }

    /*
    $faker = \Faker\Factory::create();
    $rand = rand(3000, 7000000000);
    for ($i = 1500; $i < 25000; $i++) {
      $user = User::create([
        'username' => $faker->userName,
        'identifier' => Str::upper(Str::random(5)),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'user_type_id' => UserType::all()->random()->id,
        'is_active' => true,
        'contact' => '+225 4845754864',
        'email' => "user_$i@test.fa",
        'email_verified_at' => now(),
        'department_id' => Department::all()->random()->id,
        'organization_id' => Organization::all()->random()->id,
        'employee_status_id' => EmployeeStatus::first()->id,
        'current_role_id' => Role::USER,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
      ])->assignRole(Role::USER);

      $accessCard = $user->accessCards()->create([
        'identifier' => 'CARD00' . rand(1, 999089),
        'quota_breakfast' => 25,
        'quota_lunch' => rand(0, 25),
        'payment_method_id' => 1,
      ]);

      $user->update(['current_access_card_id' => $accessCard->id]);

      foreach (\App\Models\Menu::all() as $key => $value) {
           \App\Models\Order::create([
             'user_id' => $user->id,
             'menu_id' => $value->id,
             'dish_id' => $value->mainDishes->random()->id,
             'payment_method_id' => 1,
             'old_quota_lunch' => $user->accessCard->quota_lunch,
             'new_quota_lunch' => 0,
           ]);
      }
    }*/
  }
}
