@props(['url'])
@php
    $siteTitle = \App\Models\Setting::where('key', 'site_title')->value('value') ?: config('app.name');
    $siteIcon = \App\Models\Setting::where('key', 'site_icon')->value('value');
    $logoUrl = $siteIcon ? asset('storage/' . $siteIcon) : null;
@endphp
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if ($logoUrl)
<img src="{{ $logoUrl }}" class="logo" alt="{{ $siteTitle }} Logo" style="max-height: 65px;">
@else
{{ $siteTitle }}
@endif
</a>
</td>
</tr>
