<?php

use App\Models\Role;
use App\Models\User;
use App\Models\UserType;
use App\Models\Department;
use Illuminate\Support\Str;
use App\Models\Organization;
use App\Models\EmployeeStatus;

beforeEach(function () {

    \Illuminate\Support\Facades\Artisan::call('migrate');
    \Pest\Laravel\seed();
    
    $email = 'admin-rh@pest.com';
    $this->user = User::create([
      'username' => explode('@', $email)[0],
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

    $this->accessCard = \App\Models\AccessCard::create([
      'user_id' => $this->user->id,
      'identifier' => Str::upper(Str::random(5)),
      'payment_method_id' => App\Models\PaymentMethod::first()->id,
      'quota_lunch' => 10,
      'quota_breakfast' => 0,
    ]);

});


it('can_manage_access_card', function(){
  expect($this->user->can('manage', \App\Models\AccessCard::class))->toBe(true);
});

it('can_list_user', function(){
  expect($this->user->can('viewAny', \App\Models\User::class))->toBe(true);
});

it('can_manage_order', function(){
  expect($this->user->can('manage', \App\Models\Order::class))->toBe(true);
});


it('can_manage_suggestion', function(){
  expect($this->user->can('manage', \App\Models\SuggestionBox::class))->toBe(true);
});

/*
it('can_associate_reload_access_card', function(){

});

it('can_created_exceptional_order', function(){

});

it('can_associate_access_card_by_api', function(){

});*/
