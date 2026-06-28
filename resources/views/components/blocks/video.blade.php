@props(['data' => []])
@php
    $url = $data['url'] ?? '';
    $caption = $data['caption'] ?? '';
    $embedUrl = '';
    if (preg_match('/(?:youtube\\.com\\/watch\\?v=|youtu\\.be\\/)([a-zA-Z0-9_-]+)/', $url, $m)) {
        $embedUrl = 'https://www.youtube.com/embed/' . $m[1];
    } elseif (preg_match('/(?:vimeo\\.com\\/)(\\d+)/', $url, $m)) {
        $embedUrl = 'https://player.vimeo.com/video/' . $m[1];
    }
@endphp

<section class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($embedUrl)
        <div class="aspect-w-16 aspect-h-9 rounded-2xl overflow-hidden shadow-lg">
            <iframe src="{{ $embedUrl }}" title="{{ $caption }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full"></iframe>
        </div>
        @else
        <div class="rounded-2xl overflow-hidden bg-gray-100 border-2 border-dashed border-gray-300 p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-gray-400">Masukkan URL YouTube atau Vimeo</p>
        </div>
        @endif
        @if($caption)
        <p class="text-sm text-gray-500 mt-3 text-center">{{ $caption }}</p>
        @endif
    </div>
</section>
