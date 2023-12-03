@props([
    'footer' => null,
    'header' => null,
    'reorderable' => false,
    'reorderAnimationDuration' => 300,
])

<table
    {{ $attributes->class(['min-w-full divide-y divide-gray-200 dark:divide-none']) }}
>
    @if ($header)
        <thead class="bg-secondary-700 dark:bg-white/5  rounded-t-lg">
            <tr>
                {{ $header }}
            </tr>
        </thead>
    @endif

    <tbody
        @if ($reorderable)
            x-on:end.stop="$wire.reorderTable($event.target.sortable.toArray())"
            x-sortable
            data-sortable-animation-duration="{{ $reorderAnimationDuration }}"
        @endif
        class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-none"
    >
        {{ $slot }}
    </tbody>

    @if ($footer)
        <tfoot class="bg-gray-50 dark:bg-white/5">
            <tr>
                {{ $footer }}
            </tr>
        </tfoot>
    @endif
</table>
