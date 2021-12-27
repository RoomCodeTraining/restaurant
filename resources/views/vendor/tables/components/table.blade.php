@props([
    'footer' => null,
    'header' => null,
])

<table {{ $attributes->class(['w-full text-left rtl:text-right divide-y table-auto']) }}>
    @if ($header)
        <thead>
            <tr class="bg-secondary-900">
                {{ $header }}
            </tr>
        </thead>
    @endif

    <tbody class="divide-y whitespace-nowrap">
        {{ $slot }}
    </tbody>

    @if ($footer)
        <tfoot>
            <tr class="bg-secondary-900">
                {{ $footer }}
            </tr>
        </tfoot>
    @endif
</table>
