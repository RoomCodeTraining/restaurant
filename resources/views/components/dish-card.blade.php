@props(['dish'])

<div
    class="bg-white shadow p-4 flex flex-col space-y-2 items-center justify-center">
    <img src="{{ $dish->image_url }}" class="w-24">
    <div class="flex items-center justify-between">
        <div>
            <span class="text-sm">
                {{ $dish->dishType->name }}
            </span>
        </div>
        <div>
            {{ $slot }}
        </div>
    </div>
    <div>
        <p class="font-medium text-primary-800">
            {{ $dish->name }}
        </p>
    </div>
</div>
