<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="default">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <style>
        [x-cloak] {
            display: none !important;
        }
        .customScroll::-webkit-scrollbar {
         width: 6px; /* width of the entire scrollbar */
        }

        .customScroll::-webkit-scrollbar-track {
        background: inherit; /* color of the tracking area */
        }

        .customScroll::-webkit-scrollbar-thumb {
        background-color: #f07d00; /* color of the scroll thumb */
        border-radius: 20px; /* roundness of the scroll thumb */
        border: 3px solid #f07d00; /* creates padding around scroll thumb */
        }

    </style>
    <livewire:styles />
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css" />
    @stack('styles')

    <!-- Scripts -->
    <livewire:scripts />
    <script src="{{ mix('js/app.js') }}" defer></script>
    @stack('scripts')

    <title>{{ config('app.name', 'Ciprel Cantine') }}</title>
</head>

<body class="font-sans antialiased">
    <div @keydown.escape="showModal = false" x-cloak
        x-data="{ userDropdownOpen: false, mobileSidebarOpen: false, desktopSidebarOpen: true, 'showModal': false }"
        class="flex flex-col mx-auto w-full min-h-screen bg-stone-100"
        x-bind:class="{
        'lg:pl-72': desktopSidebarOpen
    }">
        <!-- Page Sidebar -->
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
                        <x-nav-link href="{{ route('dashboard') }}" icon="home"
                            :active="request()->routeIs('dashboard')">
                            Tableau de bord
                        </x-nav-link>
                        @if (!auth()->user()->hasRole([App\Models\Role::ADMIN_LUNCHROOM, App\Models\Role::OPERATOR_LUNCHROOM]))
                            <x-nav-link href="{{ route('orders.create') }}" icon="plat"
                                :active="request()->routeIs('orders.create')">
                                Passer sa commande
                            </x-nav-link>
                            <x-nav-link href="{{ route('orders.index') }}" icon="cde"
                                :active="request()->routeIs('orders.index')">
                                Mes commandes
                            </x-nav-link>
                            <x-nav-link href="{{ route('check-in-breakfast') }}" icon="card"
                            :active="request()->routeIs('check-in-breakfast')">
                           Pontage petit dejeuner
                        </x-nav-link>
                        @endif
                        @if (auth()->user()->can('manage', \App\Models\User::class) ||
    auth()->user()->can('viewAny', \App\Models\User::class))
                            <div class="px-3 pt-5 pb-2 text-xs font-medium uppercase tracking-wider text-gray-500">
                                Gestion des comptes
                            </div>
                            @can('viewAny', \App\Models\User::class)
                                <x-nav-link href="{{ route('users.index') }}" icon="users"
                                    :active="request()->routeIs('users.*')">
                                    Utilisateurs
                                </x-nav-link>
                            @endcan
                            @can('manage', \App\Models\User::class)
                                <x-nav-link href="{{ route('roles.index') }}" icon="cog"
                                    :active="request()->routeIs('roles.index')">
                                    Rôles et permissions
                                </x-nav-link>
                            @endcan
                        @endif
                        @can('manage', \App\Models\Menu::class)
                            <div class="px-3 pt-5 pb-2 text-xs font-medium uppercase tracking-wider text-gray-500">
                                Gestion des menus
                            </div>
                            <x-nav-link href="{{ route('dishes.index') }}" icon="plat"
                                :active="request()->routeIs('dishes.index')">
                                Plats
                            </x-nav-link>
                            <x-nav-link href="{{ route('menus.index') }}" icon="menu"
                                :active="request()->routeIs('menus.index')">
                                Menus
                            </x-nav-link>
                        @endcan
                        @can('manage', \App\Models\Order::class)
                            @if(!auth()->user()->hasRole(\App\Models\Role::USER))
                                <div class="px-3 pt-5 pb-2 text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Gestion des commandes
                                </div>
                                
                                <x-nav-link href="{{ route('today.orders.summary') }}" icon="cde"
                                    :active="request()->routeIs('today.orders.summary')">
                                    Journalières
                                </x-nav-link>
                                <x-nav-link href="{{ route('orders.summary') }}" icon="cde"
                                    :active="request()->routeIs('orders.summary')">
                                    Hebdomadaires
                                </x-nav-link>
                            @endif
                        @endcan
                        @if (Gate::any(['reporting-orders', 'reporting-account']))
                            <div class="px-3 pt-5 pb-2 text-xs font-medium uppercase tracking-wider text-gray-500">
                                Reporting
                            </div>
                            <x-nav-link href="{{ route('reporting.orders') }}" icon="chart"
                                :active="request()->routeIs('reporting.orders')">
                                Commandes
                            </x-nav-link>
                        @endif
                        @if (auth()->user()->can('manage', App\Models\Department::class) ||
    auth()->user()->can('manage', App\Models\Organization::class))
                            <div class="px-3 pt-5 pb-2 text-xs font-medium uppercase tracking-wider text-gray-500">
                                Paramètrages
                            </div>
                            @can('manage', App\Models\Department::class)
                                <x-nav-link href="{{ route('departments.index') }}" icon="cube-transparent"
                                    :active="request()->routeIs('departments.index')">
                                    Départements
                                </x-nav-link>
                            @endcan
                            @can('manage', App\Models\Organization::class)
                                <x-nav-link href="{{ route('organizations.index') }}" icon="office-building"
                                    :active="request()->routeIs('organizations.index')">
                                    Sociétés
                                </x-nav-link>
                            @endcan
                            @can('manage', App\Models\UserType::class)
                                <x-nav-link href="{{ route('userTypes.index') }}" icon="users"
                                    :active="request()->routeIs('userTypes.index')">
                                    Types d'utilisateurs
                                </x-nav-link>
                            @endcan
                            @can('manage', App\Models\PaymentMethod::class)
                                <x-nav-link href="{{ route('paymentMethods.index') }}" icon="wallet"
                                    :active="request()->routeIs('paymentMethods.index')">
                                    Methodes de paiement
                                </x-nav-link>
                            @endcan
                            @can('manage', App\Models\EmployeeStatus::class)
                                <x-nav-link href="{{ route('employeeStatuses.index') }}" icon="users"
                                    :active="request()->routeIs('employeeStatuses.index')">
                                    Catégories
                                </x-nav-link>
                            @endcan
                        @endif
                        {{-- <div class="px-3 pt-5 pb-2 text-xs font-medium uppercase tracking-wider text-gray-500">
                            Reporting
                        </div> --}}
                    </nav>
                </div>
            </div>
            <!-- END Sidebar Navigation -->
        </nav>
        <!-- Page Sidebar -->

        <!-- Page Header -->
        <header id="page-header" class="flex flex-none items-center h-16 bg-white shadow fixed top-0 right-0 left-0 z-10"
            x-bind:class="{
                'lg:pl-72': desktopSidebarOpen
            }">
            <div class="flex justify-between max-w-10xl mx-auto px-4 lg:px-8 w-full">
                <!-- Left Section -->
                <div class="flex items-center space-x-2">
                    <!-- Toggle Sidebar on Desktop -->
                    <div class="hidden lg:block">
                        <button type="button"
                            class="inline-flex justify-center items-center space-x-2 border font-semibold focus:outline-none px-3 py-2 leading-6 rounded border-gray-300 bg-grey-50 bg-opacity-50 text-gray-800 shadow-sm hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 hover:shadow focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-white active:border-white active:shadow-none"
                            x-on:click="desktopSidebarOpen = !desktopSidebarOpen">
                            <svg class="hi-solid hi-menu-alt-1 inline-block w-5 h-5" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <!-- END Toggle Sidebar on Desktop -->

                    <!-- Toggle Sidebar on Mobile -->
                    <div class="lg:hidden">
                        <button type="button"
                            class="inline-flex justify-center items-center space-x-2 border font-semibold focus:outline-none px-3 py-2 leading-6 rounded border-gray-300 bg-grey-50 bg-opacity-50 text-gray-800 shadow-sm hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 hover:shadow focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-white active:border-white active:shadow-none"
                            x-on:click="mobileSidebarOpen = true">
                            <svg class="hi-solid hi-menu-alt-1 inline-block w-5 h-5" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <!-- END Toggle Sidebar on Mobile -->

                    <!-- Search -->
                    {{-- <div class="hidden sm:block">
                        <form onsubmit="return false;">
                            <input type="text"
                                class="w-full block rounded-full bg-grey-50 bg-opacity-50 border border-gray-200 px-3 py-2 leading-5 text-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50"
                                id="tk-form-layouts-search" placeholder="Rechercher.." />
                        </form>
                    </div> --}}
                    {{-- <div class="sm:hidden">
                        <button type="button"
                            class="inline-flex justify-center items-center space-x-2 border font-semibold focus:outline-none px-3 py-2 leading-5 text-sm rounded border-gray-300 bg-grey-50 bg-opacity-50 text-gray-800 shadow-sm hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 hover:shadow focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-white active:border-white active:shadow-none">
                            <svg class="hi-solid hi-search inline-block w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div> --}}
                    <!-- END Search -->
                </div>
                <!-- END Left Section -->

                <!-- Right Section -->
                <div class="flex items-center space-x-2">
                    <!-- Notifications -->
                    {{-- <button type="button"
                        class="inline-flex justify-center items-center space-x-2 border font-semibold focus:outline-none px-3 py-2 leading-5 text-sm rounded border-gray-300 bg-grey-50 bg-opacity-50 text-gray-800 shadow-sm hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 hover:shadow focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-white active:border-white active:shadow-none">
                        <svg class="hi-outline hi-bell inline-block w-5 h-5" stroke="currentColor" fill="none"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="text-primary-500">•</span>
                    </button> --}}
                    <!-- END Notifications -->

                    <!-- User Dropdown -->
                    <div class="relative inline-block">
                        <!-- Dropdown Toggle Button -->
                        <button type="button"
                            class="inline-flex justify-center items-center space-x-2 border font-semibold focus:outline-none px-3 py-2 leading-5 text-sm rounded border-gray-300 bg-grey-50 bg-opacity-50 text-gray-800 shadow-sm hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 hover:shadow focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-white active:border-white active:shadow-none"
                            id="tk-dropdown-layouts-user" aria-haspopup="true" x-bind:aria-expanded="userDropdownOpen"
                            x-on:click="userDropdownOpen = true">
                            <span>{{ Auth::user()->full_name }}</span>
                            <svg class="hi-solid hi-chevron-down inline-block w-5 h-5 opacity-50" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <!-- END Dropdown Toggle Button -->

                        <!-- Dropdown -->
                        <div x-cloak x-show="userDropdownOpen" x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="transform opacity-0 scale-75"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-75"
                            x-on:click.outside="userDropdownOpen = false" role="menu"
                            aria-labelledby="tk-dropdown-layouts-user"
                            class="absolute right-0 origin-top-right mt-2 w-48 shadow-xl rounded z-1">
                            <div class="bg-white ring-1 ring-black ring-opacity-5 rounded divide-y divide-gray-100">
                                <div class="p-2 space-y-1">
                                    <a role="menuitem" href="{{ route('profile') }}"
                                        class="flex items-center space-x-2 rounded py-2 px-3 text-sm font-medium text-gray-600 hover:bg-primary-900 hover:text-gray-50 focus:outline-none focus:bg-gray-100 focus:text-gray-700">
                                        <svg class="hi-solid hi-user-circle inline-block w-5 h-5 opacity-50"
                                            fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span>Mon Profil</span>
                                    </a>
                                </div>
                                <div class="p-2 space-y-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" role="menuitem"
                                            class="w-full text-left flex items-center space-x-2 rounded py-2 px-3 text-sm font-medium text-gray-600 hover:bg-primary-900 hover:text-gray-50 focus:outline-none focus:bg-gray-100 focus:text-gray-700">
                                            <svg class="hi-solid hi-lock-closed inline-block w-5 h-5 opacity-50"
                                                fill="currentColor" viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span>Se déconnecter</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- END Dropdown -->
                    </div>
                    <!-- END User Dropdown -->
                </div>
                <!-- END Right Section -->
            </div>
        </header>
        <!-- END Page Header -->
        <!-- Page Content -->
        <main id="page-content" class="flex flex-auto flex-col bg-stone-100 max-w-full pt-16">
            <!-- Page Section -->
            <div class="max-w-10xl mx-auto p-4 lg:p-8 w-full">
                @include('partials.flasher')
                {{ $slot }}
            </div>
            <!-- END Page Section -->
        </main>
        <!-- END Page Content -->
    </div>
    <!-- END Page Container -->
</body>

</html>
