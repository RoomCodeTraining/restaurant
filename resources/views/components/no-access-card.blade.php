@if (!auth()->user()->current_access_card_id)
<div class="alert mb-3">
    <div class="flex-1">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#2196f3"
            class="w-6 h-6 mx-2">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <label class='font-semibold'>
            Bonjour Mr/Mme {{ auth()->user()->full_name }} vous n'avez pas une Carte RFID associé a votre compte cantine. Veuillez contactez le responsable pour avoir une carte.
        </label>
    </div>
</div>
@endif
