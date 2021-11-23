<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img class="logo" src="{{ asset('images/logo-ciprel-vf.png') }}">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
