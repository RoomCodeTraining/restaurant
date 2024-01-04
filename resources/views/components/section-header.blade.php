<div class="header mb-2 md:flex justify-between">
    <div class="title">
        <h1 class="text-3xl font-bold text-black mt-3">{{ $title }}</h1>
        @isset($description)
            <p class="mt-1 text-sm text-gray-600">
                {{ $description }}
            </p>
            @endif
        </div>
        @isset($actions)
            <div class="actions md:flex self-start space-x-2 mt-3">
                {{ $actions }}
            </div>
        @endisset
    </div>
<hr class="mb-5 border border-grey-900">
