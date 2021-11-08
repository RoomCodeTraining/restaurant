@props(['active', 'icon' => 'grid'])

@php
$classes = $active ?? false ? 'flex items-center space-x-3 px-3 font-medium rounded text-gray-700 bg-primary-100' : 'flex items-center space-x-3 px-3 font-medium rounded text-gray-600 hover:text-gray-700 hover:bg-primary-100 active:bg-primary-50';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <span class="flex-none flex items-center opacity-50">
        <x-icon name="{{ $icon }}" />
    </span>
    <span class="py-2 flex-grow">
        {{ $slot }}
    </span>
</a>
