@if($order_is_suspended)
    <div class="alert mb-3">
        <div class="flex-1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#2196f3"
                class="w-6 h-6 mx-2">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <label>
                Il existe des commandes qui on été suspendues a cause du changement de menu. Veuillez consulter vos commandes suspendues
                <a href='{{ route('orders.index') }}' class="font-bold" title='Consulter le menu'>Consulter</a>
            </label>
        </div>
    </div>
@endif
