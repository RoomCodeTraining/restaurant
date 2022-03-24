<x-app-layout>
    <x-order-suspended />
    @hasrole(\App\Models\Role::ADMIN)
        <livewire:dashboards.admin-dashboard>
        @elseif(auth()->user()->hasRole(\App\Models\Role::USER))
            <livewire:dashboards.user-dashboard>
            @elseif(auth()->user()->hasRole(\App\Models\Role::ADMIN_RH))
                <livewire:dashboards.admin-rh-dashboard />
            @elseif(auth()->user()->hasRole(\App\Models\Role::ADMIN_LUNCHROOM) ||
    auth()->user()->hasRole(\App\Models\Role::OPERATOR_LUNCHROOM))
                <livewire:dashboards.admin-cantine-dashboard />
            @elseif(auth()->user()->hasRole(\App\Models\Role::ACCOUNTANT))
                <livewire:dashboards.admin-accountant-dashboard />
            @else
                <div class="alert">
                    <div class="flex-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#2196f3"
                            class="w-6 h-6 mx-2">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <label>Bienvenue {{ Auth::user()->full_name }}!</label>
                    </div>
                </div>
            @endhasrole
</x-app-layout>
