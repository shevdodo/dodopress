@props(['data' => []])
@php
    $title = $data['title'] ?? 'Siap Memulai?';
    $description = $data['description'] ?? '';
    $buttonText = $data['button_text'] ?? 'Hubungi Kami';
    $buttonLink = $data['button_link'] ?? '#';
    $bgColor = $data['bg_color'] ?? 'brand';
    $bgClass = match($bgColor) {
        'dark' => 'bg-gray-900 text-white',
        'light' => 'bg-gray-100 text-gray-900',
        default => 'bg-brand-600 text-white',
    };
    $btnClass = match($bgColor) {
        'dark' => 'bg-white text-gray-900 hover:bg-gray-100',
        'light' => 'bg-brand-600 text-white hover:bg-brand-700',
        default => 'bg-white text-brand-600 hover:bg-gray-100',
    };
@endphp

<section class="py-20 {{ $bgClass }}">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl font-bold mb-4">{{ $title }}</h2>
        @if($description)
        <p class="text-lg opacity-90 mb-8 max-w-2xl mx-auto">{{ $description }}</p>
        @endif
        <a href="{{ $buttonLink }}" class="inline-flex items-center px-8 py-3.5 {{ $btnClass }} font-semibold rounded-xl transition shadow-lg">
            {{ $buttonText }}
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>
