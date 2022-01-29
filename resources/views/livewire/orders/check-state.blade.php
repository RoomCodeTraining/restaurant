@php
    $classes = [
        \App\States\Order\Confirmed::$name => 'badge badge-error',
        \App\States\Order\Completed::$name => 'badge badge-success',
    ]
@endphp

<div class="{{ $classes[$order->state::$name] }}">
    {{ $order->state == 'confirmed' ? 'Non consommé' : 'Consommé'  }}
</div>
