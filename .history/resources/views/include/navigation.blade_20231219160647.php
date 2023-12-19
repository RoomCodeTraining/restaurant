<nav id="page-sidebar"
    class="flex flex-col fixed top-0 left-0 bottom-0 w-full lg:w-72 h-full bg-gray-900 border-r border-gray-100 transform transition-transform duration-100 ease-out z-20"
    x-bind:class="{
        '-translate-x-full': !mobileSidebarOpen,
        'translate-x-0': mobileSidebarOpen,
        'lg:-translate-x-full': !desktopSidebarOpen,
        'lg:translate-x-0': desktopSidebarOpen,
    }"
    aria-label="Sidebar">
    <!-- Sidebar Header -->
    <div class="h-16 flex-none flex items-center justify-between lg:justify-center px-4 w-full">
        <!-- Brand -->
        <a href="{{ route('dashboard') }}"
            class="inline-flex items-center font-bold text-lg tracking-wide text-gray-600 hover:text-gray-500">
            <x-application-logo class="h-12" />
        </a>
        <div class="lg:hidden">
            <button type="button"
                class="inline-flex justify-center items-center space-x-2 border font-semibold focus:outline-none px-3 py-2 leading-5 text-sm rounded border-transparent text-red-600 hover:text-red-400 focus:ring focus:ring-red-500 focus:ring-opacity-50 active:text-red-600"
                x-on:click="mobileSidebarOpen = false">
                <svg class="hi-solid hi-x inline-block w-4 h-4 -mx-1" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
    <div class="overflow-y-auto customScroll">
        <div class="p-4 w-full">
            <nav class="space-y-1">
                <x-nav-link href="{{ route('dashboard') }}" icon="home" :active="request()->routeIs('dashboard')">
                    Tableau de bord
                </x-nav-link>
                @if (!auth()->user()->hasRole([App\Models\Role::ADMIN_LUNCHROOM, App\Models\Role::OPERATOR_LUNCHROOM]))
                    <x-nav-link href="{{ route('orders.create') }}" icon="plat" :active="request()->routeIs('orders.create')">
                        Passer sa commande
                    </x-nav-link>
                    <x-nav-link href="{{ route('orders.index') }}" icon="cde" :active="request()->routeIs('orders.index')">
                        Mes commandes
                    </x-nav-link>
                    <x-nav-link href="{{ route('check-in-breakfast') }}" icon="card" :active="request()->routeIs('check-in-breakfast')">
                        Mes Historiques
                    </x-nav-link>
                @endif
                @if (auth()->user()->can('manage', \App\Models\User::class) ||
                        auth()->user()->can('viewAny', \App\Models\User::class) ||
                        auth()->user()->hasRole(App\Models\Role::ADMIN_TECHNICAL))
                    <div class="px-3 pt-5 pb-2 text-xs font-medium uppercase tracking-wider text-gray-500">
                        Gestion des comptes
                    </div>
                    @can('viewAny', \App\Models\User::class)
                        <x-nav-link href="{{ route('users.index') }}" icon="users" :active="request()->routeIs('users.*')">
                            Utilisateurs
                        </x-nav-link>
                    @endcan
                    @if (auth()->user()->hasRole(App\Models\Role::ADMIN_RH))
                        <x-nav-link href="{{ route('access-cards.reloads.history') }}" icon="users" :active="request()->routeIs('access-cards.reloads.history')">
                            Historique des recharges
                        </x-nav-link>
                        <x-nav-link href="{{ route('access-cards.index') }}" icon="users" :active="request()->routeIs('access-cards.histories')">
                            Liste des cartes
                        </x-nav-link>
                    @endif
                    @if (auth()->user()->hasRole(App\Models\Role::ADMIN_TECHNICAL))
                        <x-nav-link href="{{ route('roles.index') }}" icon="cog" :active="request()->routeIs('roles.index')">
                            Rôles et permissions
                        </x-nav-link>
                        <x-nav-link href="{{ route('activities-log') }}" icon="box_" :active="request()->routeIs('activities-log')">
                            Activités
                        </x-nav-link>
                    @endif
                @endif
                @can('manage', \App\Models\Menu::class)
                    <div class="px-3 pt-5 pb-2 text-xs font-medium uppercase tracking-wider text-gray-500">
                        Gestion des menus
                    </div>
                    <x-nav-link href="{{ route('dishes.index') }}" icon="plat" :active="request()->routeIs('dishes.index')">
                        Plats
                    </x-nav-link>
                    <x-nav-link href="{{ route('menus.index') }}" icon="menu" :active="request()->routeIs('menus.index')">
                        Menus A
                    </x-nav-link>
                    <x-nav-link href="{{ route('menus-specials.index') }}" icon="menu" :active="request()->routeIs('menus-specials.index')">
                        Menus B
                    </x-nav-link>
                @endcan
                @can('manage', \App\Models\Order::class)
                    @if (!auth()->user()->hasRole(\App\Models\Role::USER))
                        <div class="px-3 pt-5 pb-2 text-xs font-medium uppercase tracking-wider text-gray-500">
                            Gestion des commandes
                        </div>
                        <x-nav-link href="{{ route('today.orders.summary') }}" icon="cde" :active="request()->routeIs('today.orders.summary')">
                            Journalières
                        </x-nav-link>
                        <x-nav-link href="{{ route('orders.summary') }}" icon="cde" :active="request()->routeIs('orders.summary')">
                            Hebdomadaires
                        </x-nav-link>
                    @endif
                @endcan
                @if (Gate::any(['reporting-orders', 'reporting-account']))
                    <div class="px-3 pt-5 pb-2 text-xs font-medium uppercase tracking-wider text-gray-500">
                        Reporting
                    </div>
                    <x-nav-link href="{{ route('reporting.check.breakfast') }}" icon="chart" :active="request()->routeIs('reporting.check.breakfast')">
                        Petit déjeuner
                    </x-nav-link>
                    <x-nav-link href="{{ route('reporting.orders') }}" icon="chart" :active="request()->routeIs('reporting.orders')">
                        Déjeuner
                    </x-nav-link>
                @endif
                @if (auth()->user()->can('manage', \App\Models\SuggestionBox::class) ||
                        auth()->user()->can('viewAny', \App\Models\SuggestionBox::class))
                    <div class="px-3 pt-5 pb-2 text-xs font-medium uppercase tracking-wider text-gray-500">
                        Suggestions
                    </div>
                    <x-nav-link href="{{ route('suggestions-box.index') }}" icon="box_" :active="request()->routeIs('suggestions-box.index')">
                        Boîte à suggestions
                    </x-nav-link>
                @endif
                <!--  @if (auth()->user()->hasRole(\App\Models\Role::ADMIN))
<div class="px-3 pt-5 pb-2 text-xs font-medium uppercase tracking-wider text-gray-500">
                                               Statistiques
                                           </div>
                                           <x-nav-link href="{{ route('dishes.stats') }}" icon="stats"
                                               :active="request()->routeIs('dishes.stats')">
                                               Plats
                                           </x-nav-link>
                                           <x-nav-link href="{{ route('users.stats') }}" icon="stats"
                                               :active="request()->routeIs('users.stats')">
                                               Utilisateurs
                                           </x-nav-link>
@endif-->
                @if (auth()->user()->can('manage', App\Models\Department::class) ||
                        auth()->user()->can('manage', App\Models\Organization::class))
                    <div class="px-3 pt-5 pb-2 text-xs font-medium uppercase tracking-wider text-gray-500">
                        Paramètrages
                    </div>
                    @can('manage', App\Models\Department::class)
                        <x-nav-link href="{{ route('departments.index') }}" icon="cube-transparent" :active="request()->routeIs('departments.index')">
                            Départements
                        </x-nav-link>
                    @endcan
                    @can('manage', App\Models\Organization::class)
                        <x-nav-link href="{{ route('organizations.index') }}" icon="office-building" :active="request()->routeIs('organizations.index')">
                            Sociétés
                        </x-nav-link>
                    @endcan
                    @can('manage', App\Models\UserType::class)
                        <x-nav-link href="{{ route('userTypes.index') }}" icon="users" :active="request()->routeIs('userTypes.index')">
                            Types d'utilisateurs
                        </x-nav-link>
                    @endcan
                    @can('manage', App\Models\PaymentMethod::class)
                        <x-nav-link href="{{ route('paymentMethods.index') }}" icon="wallet" :active="request()->routeIs('paymentMethods.index')">
                            Methodes de paiement
                        </x-nav-link>
                    @endcan
                    @can('manage', App\Models\EmployeeStatus::class)
                        <x-nav-link href="{{ route('employeeStatuses.index') }}" icon="users" :active="request()->routeIs('employeeStatuses.index')">
                            Catégories
                        </x-nav-link>
                    @endcan
                    <!--@if (auth()->user()->isAdmin())
<x-nav-link href="totem/tasks" icon="users"
                                                   :active="request()->routeIs('employeeStatuses.index')">
                                                   Planificateur des tâches
                                               </x-nav-link>
@endif-->
                @endif
                @if (auth()->user()->hasRole(\App\Models\Role::ADMIN_TECHNICAL))
                    <div class="px-3 pt-5 pb-2 text-xs font-medium uppercase tracking-wider text-gray-500">
                        Paramètrages
                    </div>
                    <x-nav-link href="totem/tasks" icon="users">
                        Planificateur des tâches
                    </x-nav-link>
                @endif
            </nav>
        </div>
    </div>
</nav>
