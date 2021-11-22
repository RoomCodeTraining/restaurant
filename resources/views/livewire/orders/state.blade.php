@php
    $classes = [
        \App\States\Order\Confirmed::$name => 'badge badge-warning',
        \App\States\Order\Cancelled::$name => 'badge badge-error',
        \App\States\Order\Completed::$name => 'badge badge-success',
    ]
@endphp

<div class="{{ $classes[$order->state::$name] }}">
    {{ $order->state::title() }}
</div>
