<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use App\Policies\ReportingPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Menu::class => \App\Policies\MenuPolicy::class,
        \App\Models\Dish::class => \App\Policies\DishPolicy::class,
        \App\Models\Order::class => \App\Policies\OrderPolicy::class,
        \App\Models\AccessCard::class => \App\Policies\AccessCardPolicy::class,
        \App\Models\Department::class => \App\Policies\DepartmentPolicy::class,
        \App\Models\Organization::class => \App\Policies\OrganizationPolicy::class,
        \App\Models\EmployeeStatus::class => \App\Policies\EmployeeStatusPolicy::class,
        \App\Models\UserType::class => \App\Policies\UserTypePolicy::class,
        \App\Models\PaymentMethod::class => \App\Policies\PaymentMethodPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('reporting-orders', [ReportingPolicy::class, 'viewOrders']);
        Gate::define('reporting-account', [ReportingPolicy::class, 'viewAccount']);

        Gate::define('view-data', function (User $logginInUser) {
            return $logginInUser->hasRole(Role::DATA_ANALYST);
        });
    }
}
