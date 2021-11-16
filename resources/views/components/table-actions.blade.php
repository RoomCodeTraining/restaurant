@php($params = is_array($row) ? $row['id'] : $row->id)

<td class="px-6 py-4 whitespace-nowrap border-b border-gray-200 text-sm leading-5 text-gray-500">
    @foreach($actions as $action_name => $action_route)
        <a href="{{ \Illuminate\Support\Facades\Route::has($action_route) ? route($action_route, $params ?? []) : url($action_route) }}"
           class="text-indigo-600 hover:text-indigo-900 capitalize">
            {{ $action_name }}
        </a>
    @endforeach
</td>
