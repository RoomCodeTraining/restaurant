@props(['options' => []])

@php
$options = array_merge(
    [
        'dateFormat' => 'Y-m-d',
        'enableTime' => false,
        'altFormat' => 'j F Y',
        'altInput' => true,
        'locale' => 'fr'
    ],
    $options,
);
@endphp

<div class="relative" wire:ignore>
    <input x-data="{
           init() {
               flatpickr($refs.input, {{ json_encode((object) $options) }});
           }
        }" x-ref="input" type="text"
        {{ $attributes->merge(['class' => 'block w-full border-gray-300 rounded-md shadow-sm transition duration-150 ease-in-out focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:text-white dark:border-gray-600']) }} />
    <div class="absolute top-0 right-0 px-3 py-3" data-toggle>
        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
    </div>
</div>

@once
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endpush

    @push('scripts')
        <script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
        <script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>
    @endpush
@endonce
