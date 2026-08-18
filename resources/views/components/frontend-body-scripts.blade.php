@php
    $headerBodyScripts = \App\Models\Setting::where('key', 'header_body_scripts')->value('value');
@endphp
@if($headerBodyScripts)
{!! $headerBodyScripts !!}
@endif
