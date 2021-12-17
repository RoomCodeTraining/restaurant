@php
    $classes = [
        \App\States\Order\Confirmed::$name => 'badge badge-success',
        \App\States\Order\Cancelled::$name => 'badge badge-error',
        \App\States\Order\Suspended::$name => 'badge badge-neutral',
        \App\States\Order\Completed::$name => 'badge badge-success',
    ]
@endphp

<div class="{{ $classes[$order->state::$name] }}">
    {{ $order->state::title() }}
</div>
