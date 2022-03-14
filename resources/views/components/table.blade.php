@props(['columns', 'rows', 'actions' => null, 'actionsCustomView' => null, 'hover' => true, 'no-shadow' => false, 'stripped' => true])

<div {{ $attributes->merge(['class' => 'inline-block min-w-full rounded-lg overflow-y-scroll']) }}>
    <table class="min-w-full bg-white">
        <thead class="bg-secondary-200">
            <tr>
                @foreach ($columns as $column_name => $column_key)
                    <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-secondary-900 text-left text-xs font-semibold text-white uppercase tracking-wider">
                        {{ $column_name }}
                    </th>
                @endforeach
                @if ($actions)
                    <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-secondary-900 text-left text-xs font-semibold text-white uppercase tracking-wider">
                    </th>
                @endif
            </tr>
        </thead>
        <tbody>
            @if (count($rows))
                @foreach ($rows as $row)
                    <tr
                        class="{{ $hover ? 'hover:bg-gray-100' : '' }} {{ $stripped ? ($loop->even ? 'bg-gray-200' : '') : '' }}">
                        @foreach ($columns as $column_name => $column_key)
                            <td class="px-3 py-3 border-b border-gray-200 bg-white text-sm">
                                @if (is_array($row))
                                    {{ $row[$column_key] }}
                                @else
                                    @if (is_callable($column_key))
                                        {{ $column_key($row) }}
                                    @else
                                        {{ data_get($row, $column_key) }}
                                    @endif
                                @endif
                            </td>
                        @endforeach
                        @includeWhen($actions, $actionsCustomView ? $actionsCustomView : 'components.table-actions',
                        compact('row', 'actions'))
                    </tr>
                @endforeach
            @else
                <tr class="text-center {{ $hover ? 'hover:bg-gray-100' : '' }}">
                    @isset($emptySlot)
                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                            {{ $emptySlot }}
                        </td>
                    @else
                        <td colspan="{{ count($columns) }}"
                            class="px-6 py-4 whitespace-nowrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                            Aucune donnée
                        </td>
                    @endisset
                </tr>
            @endif
        </tbody>
    </table>
</div>
