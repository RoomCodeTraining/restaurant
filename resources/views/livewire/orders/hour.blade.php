<div class="badge {{ $order->is_for_the_evening ? 'badge-error' : 'badge-success' }}">
  <x-icon name="{{ $order->is_for_the_evening ? 'sun' : 'sun-fill' }}" class="h-4 w-4 m-1" />
   {{ $order->is_for_the_evening ? 'Nuit' : 'Jour' }}
</div>
