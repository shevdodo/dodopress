@props(['data' => []])
@php
    $badge = $data['badge'] ?? 'Selamat Datang';
    $title = $data['title'] ?? 'Bangun Website Impian Anda';
    $subtitle = $data['subtitle'] ?? '';
    $buttonText = $data['button_text'] ?? '';
    $buttonLink = $data['button_link'] ?? '#';
    $bgImage = $data['bg_image'] ?? '';
@endphp

<section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white py-20 lg:py-32">
    @if($bgImage)
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-30" style="background-image: url('{{ $bgImage }}')"></div>
    <div class="absolute inset-0 bg-gradient-to-br from-gray-900/80 via-gray-800/80 to-gray-900/80"></div>
    @endif
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        @if($badge)
        <span class="inline-block px-4 py-1.5 rounded-full bg-white/10 text-sm font-medium text-white/90 mb-6 border border-white/10">
            {{ $badge }}
        </span>
        @endif
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight mb-6">
            {!! nl2br(e($title)) !!}
        </h1>
        @if($subtitle)
        <p class="text-lg sm:text-xl text-gray-300 max-w-3xl mx-auto mb-10 leading-relaxed">
            {{ $subtitle }}
        </p>
        @endif
        @if($buttonText)
        <a href="{{ $buttonLink }}" class="inline-flex items-center px-8 py-3.5 bg-brand-500 hover:bg-brand-600 text-white font-semibold rounded-xl transition shadow-lg shadow-brand-500/30">
            {{ $buttonText }}
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
        @endif
    </div>
</section>
