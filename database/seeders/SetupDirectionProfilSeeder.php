<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Policies\DepartmentPolicy;
use App\Policies\EmployeeStatusPolicy;
use App\Policies\OrderPolicy;
use App\Policies\OrganizationPolicy;
use App\Policies\PaymentMethodPolicy;
use App\Policies\ReportingPolicy;
use App\Policies\SuggestionBoxPolicy;
use App\Policies\UserPolicy;
use App\Policies\UserTypePolicy;
use Illuminate\Database\Seeder;

class SetupDirectionProfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Role::create([
        //     'id' => Role::ADMIN_TECHNICAL,
        //     'name' => 'Admin Technique',
        // ])->givePermissionTo([UserPolicy::USER_MANAGE, OrderPolicy::ORDER_MANAGE, OrganizationPolicy::ORGANIZATION_MANAGE, DepartmentPolicy::DEPARTMENT_MANAGE, PaymentMethodPolicy::PAYMENT_METHOD_MANAGE, EmployeeStatusPolicy::EMPLOYEE_STATUS_MANAGE, UserTypePolicy::USER_TYPE_MANAGE, ReportingPolicy::REPORTING_ORDERS, SuggestionBoxPolicy::SUGGESTION_LIST, SuggestionBoxPolicy::SUGGESTION_MANAGE]);

        // $role = Role::create([
        //     'id' => Role::DATA_ANALYST,
        //     'name' => 'Top Manager',
        // ])->givePermissionTo([OrderPolicy::ORDER_MANAGE, SuggestionBoxPolicy::SUGGESTION_MANAGE]);
        $data = Permission::create([
            'name' => 'data.*',
            'description' => 'Toutes les permissions sur la vue data',
        ]);

        $data->children()->saveMany([
            new Permission([
                'name' => 'data.view',
                'description' => 'Consulter la vue data',
            ]),
        ]);


    }
}