@props(['dish'])

<div class="bg-white shadow p-4 flex flex-col space-y-2 items-center justify-center">
    <img src="{{ $dish->image_url }}" class="w-24">
    <div class="product__info">
        <p class="font-medium text-primary-800">
            {{ $dish->name }}
        </p>
    </div>
    <div class="flex items-center justify-between">
        <div class="product-price layout-vertical-tablet">
            <span class="text-sm">
                {{ $dish->dishType->name }}
            </span>
        </div>
        <div>
            {{ $slot }}
        </div>
    </div>
</div>
