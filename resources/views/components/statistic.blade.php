@props(['label', 'value', 'icon'])

<!-- Card: Simple Widget with Icon -->
<div class="flex flex-col rounded shadow-lg bg-white overflow-hidden hover:shadow-sm hover:cursor-pointer">
    <!-- Card Body: Simple Widget with Icon -->
    <div class="p-5 lg:p-6 flex-grow w-full flex justify-between items-center">
        <div class="flex justify-center items-center w-16 h-16 border-r-2 border-primary-600">
            <x-icon name="{{ $icon }}" class="w-8 h-8 text-primary-600" />
        </div>
        <dl class="text-right">
            <dt class="text-2xl font-bold text-secondary-900">
                {{ $value }}
            </dt>
            <dd class="uppercase font-medium text-sm text-gray-500 tracking-wider">
                {{ $label }}
            </dd>
        </dl>
    </div>
    <!-- END Card Body: Simple Widget with Icon -->
</div>
<!-- END Card: Simple Widget with Icon -->
