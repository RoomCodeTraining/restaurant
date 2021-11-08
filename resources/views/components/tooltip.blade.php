@props(['content', 'placement' => 'top', 'trigger' => 'mouseenter', 'appendTo' => 'button', 'noArrow' => false])

@push('after-scripts')
<script>
window.addEventListener('DOMContentLoaded', () => {
    if (Tippy !== undefined) {
        Tippy.default('{{ $appendTo }}', {
            content: '{{ $content }}',
            duration: 500,
            @unless($noArrow)
                {{ 'arrow: true,' }}
            @else
                {{ 'arrow: false,' }}
            @endunless
            trigger: '{{ $trigger }}',
            animation: 'fade',
            placement: '{{ $placement }}'
        });
    }
})
</script>
@endpush
