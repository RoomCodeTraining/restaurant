<x-app-layout>
    @hasrole(\App\Models\Role::SUPER_ADMIN)
        <livewire:dashboard.super-admin-dashboard>
    @else
        <div>
            Default dashboard
        </div>
    @endhasrole
</x-app-layout>
