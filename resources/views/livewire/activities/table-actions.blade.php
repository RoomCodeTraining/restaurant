<div class="flex items-center space-x-2">
  <div x-data="{ tooltip: 'Consulter' }">
      <a href="{{ route('activity-log.show', $activity) }}" x-tooltip="tooltip">
          <x-icon name="eye" class="h-4 w-4 text-secondary-800" />
      </a>
  </div>
</div>
