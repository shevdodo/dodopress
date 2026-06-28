@props(['data' => []])
@php
    $height = $data['height'] ?? 'md';
    $hClass = match($height) {
        'sm' => 'h-8',
        'lg' => 'h-24',
        'xl' => 'h-32',
        default => 'h-16',
    };
@endphp

<div class="{{ $hClass }}"></div>
