<x-app-layout>
    <div class="container mx-auto">
        <x-section-header title='Information du compte' />
        @if (!auth()->user()->isFromLunchroom())
            <livewire:account.order-config-form />
        @endif

    </div>
</x-app-layout>
