<?php

use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use App\Models\Order;
use Livewire\Livewire;
use App\Models\AccessCard;
use Illuminate\Support\Str;
use function Pest\Livewire\livewire;
use Illuminate\Support\Facades\Auth;
use App\Actions\User\CreateUserAction;
use Illuminate\Http\Response;

use App\Http\Livewire\Users\CreateUserForm;
use App\Http\Livewire\Orders\CreateOrderForm;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

beforeEach(function () {
  \Illuminate\Support\Facades\Artisan::call('migrate');
  \Pest\Laravel\seed();

  $email = 'user@pest.com';
  $this->user = User::create([
    'username' => explode('@', $email)[0],
    'identifier' => 'TEST7',
    'first_name' => 'Da',
    'last_name' => 'Sié Roger',
    'email' => $email,
    'contact' => '0612345678',
    'current_role_id' => Role::USER,
    'organization_id' => \App\Models\Organization::first()->id,
    'user_type_id' => \App\Models\UserType::first()->id,
    'department_id' => \App\Models\Department::first()->id,
    'organization_id' => \App\Models\Organization::first()->id,
    'employee_status_id' => \App\Models\EmployeeStatus::first()->id,
  ]);

  $this->user->assignRole(Role::USER);
});



it('logged_to_list_all_users', function () {
  $response = $this->get(route('users.index'));
  $response->assertRedirect(route('login'));
});

it('logged_to_list_all_menus', function () {
  $response = $this->get(route('menus.index'));
  $response->assertRedirect(route('login'));
});

it('logged_to_list_all_departments', function () {
  $response = $this->get(Route('departments.index'));
  $response->assertRedirect(route('login'));
});


it('logged_to_list_all_user_types', function () {
  $response = $this->get(Route('userTypes.index'));
  $response->assertRedirect(route('login'));
});


it('logged_to_list_all_employee_statuses', function () {
  $response = $this->get(Route('employeeStatuses.index'));
  $response->assertRedirect(route('login'));
});


it('logged_to_list_all_dishes', function () {
  $response = $this->get(Route('dishes.index'));
  $response->assertRedirect(route('login'));
});

it('user_has_no_access_card', function () {
  expect($this->user->current_access_card_id)->toEqual(null);
});

it('shows_users_list', function () {
  $this->user->assignRole(Role::ADMIN);
  $response = $this->actingAs($this->user)->get(route('users.index'));
  $response->assertStatus(302);
});

it('can_shows_user_created_page', function(){
    $this->user->assignRole(Role::ADMIN);
    $response = $this->actingAs($this->user)->get(route('users.create'));
    $response->assertStatus(302);
});

it('can_show_created_dish_page', function(){
    $this->user->assignRole(Role::ADMIN);
    $this->actingAs($this->user)->get(route('dishes.create'));
    $this->actingAs($this->user)->get(route('dishes.create'))->assertForbidden();
});

it('associate_access_card', function () {

  $this->card = $this->user->accessCards()->create([
    'identifier' => Str::random(12),
    'quota_lunch' => 1,
    'quota_breakfast' => 1,
    'payment_method_id' => \App\Models\PaymentMethod::first()->id,
  ]);

  $this->user->useCard($this->card);
  expect($this->user->current_access_card_id)->toEqual($this->card->id);
});


it('user_can_created_order', function () {
  expect($this->user->can('create', Order::class))->toBe(true);
});


it('user_has_no_order', function () {
  expect($this->user->orders->count())->toEqual(0);
});

it('user_has_quota_to_order', function () {

  $this->card = $this->user->accessCards()->create([
    'identifier' => Str::random(12),
    'quota_lunch' => 10,
    'quota_breakfast' => 10,
    'payment_method_id' => \App\Models\PaymentMethod::first()->id,
  ]);


  $this->user->useCard($this->card);
  expect($this->user->accessCard->quota_lunch > 0)->toBe(true);
});



