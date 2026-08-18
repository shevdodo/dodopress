@php
    $headerScripts = \App\Models\Setting::where('key', 'header_scripts')->value('value');
@endphp
@if($headerScripts)
{!! $headerScripts !!}
@endif
