@props(['dish', 'menu'])

<div class="{{ today()->equalTo($menu->served_at) ? 'bg-white' : 'bg-gray-300' }} shadow p-4 flex flex-col space-y-2 items-center justify-center">
    <img src="{{ $dish->image_url }}" class="w-24">
    <div>
        <p class="font-medium text-primary-800">
            {{ $dish->name }}
        </p>
    </div>
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
</div>
