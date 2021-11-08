@props(['label', 'value', 'icon'])

<!-- Card: Simple Widget with Icon -->
<div class="flex flex-col rounded shadow-sm bg-white overflow-hidden">
    <!-- Card Body: Simple Widget with Icon -->
    <div class="p-5 lg:p-6 flex-grow w-full flex justify-between items-center">
        <div class="flex justify-center items-center rounded-xl w-16 h-16 bg-primary-100">
            <x-icon name="{{ $icon }}" class="w-8 h-8 text-primary-400" />
        </div>
        <dl class="text-right">
            <dt class="text-2xl font-semibold">
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
