@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-4">
        @isset($title)
            <div class="text-lg font-semibold">
                {{ $title }}
            </div>
        @endisset

        <div class="mt-4 content">
            {{ $content }}
        </div>
    </div>

    @isset($footer)
        <div class="px-6 py-4 bg-grey-800 text-right flex justify-end">
            {{ $footer }}
        </div>
    @endif

  <style>
    .content {
        max-height: calc(100vh - 400px);
        overflow-y: auto;
    }
  </style>
</x-modal>
