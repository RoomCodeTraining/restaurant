@props(['submit'])

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    <form wire:submit.prevent="{{ $submit ?? '' }}">
        <div class="shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 bg-white sm:p-6">
                <div class="">
                    {{ $form }}
                </div>
            </div>

            @if (isset($actions))
                <div class="flex items-center justify-end px-4 py-3 bg-white text-right sm:px-6">
                    {{ $actions }}
                </div>
            @endif
        </div>
    </form>
</div>
