<div>
    <x-no-access-card />
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-8">
        @if (auth()->user()->current_access_card_id)
            <a href='{{ route('orders.index') }}'>
                @if ($today_order && $today_order->state == 'completed')
                    <x-statistic label="Commande du jour" value="{{ 'Deja consommée' }}" icon="plat" />
                @else
                    <x-statistic label="Commande du jour" value="{{ $today_order ? $today_order->dish->name : 'Aucune' }}"
                        icon="plat" />
                @endif
            </a>
            <a href='/profile#card'>
                <x-statistic label="quota petit dejeuner"
                    value="{{ auth()->user()->currentAccessCard->quota_breakfast }}" icon="card" />
            </a>
            <a href='/profile#card'>
                <x-statistic label="quota dejeuner" value="{{ auth()->user()->currentAccessCard->quota_lunch }}"
                    icon="card" />
            </a>
        @endif
        <a href='{{ route('users.index') }}'>
            <x-statistic label="Utilisateurs" value="{{ $users_count }}" icon="users" />
        </a>
        <a href='{{ config('app.url') }}/users?filters[active]=yes'>
            <x-statistic label="Utilisateurs actifs" value="{{ $active_users_count }}" icon="actifuser" />
        </a>
        <a href='{{ config('app.url') }}/users?filters[active]=no'>
            <x-statistic label="Utilisateurs inactifs" value="{{ $inactive_users_count }}" icon="inactifuser" />
        </a>
    </div>

    <div class="flex">
        <div class="container mx-auto space-y-4  sm:p-0 mt-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="shadow rounded  border bg-white" style="height: 22rem;">
                    {{-- @livewire(\App\Http\Livewire\OtherProfil\ManagerStatisticsChart::class) --}}
                </div>
                <div class="shadow rounded  border bg-white" style="height: 22rem;">
                    @livewire(\App\Http\Livewire\OtherProfil\HighConsumptionChart::class)
                </div>
                <div class="shadow rounded  border bg-white " style="height: 22rem;">
                    {{-- @livewire(\App\Http\Livewire\OtherProfil\CategoryEmployeeConsumptionChart::class) --}}
                </div>

                {{-- <div class="shadow rounded  border bg-white" style="height: 22rem;">
                    @livewire(\App\Http\Livewire\OtherProfil\ManagerStatisticsChart::class)
                </div> --}}

            </div>
        </div>
    </div>

</div>
