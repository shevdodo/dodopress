@props(['data' => []])
@php
    $src = $data['src'] ?? '';
    $alt = $data['alt'] ?? 'Image';
    $caption = $data['caption'] ?? '';
@endphp

<section class="py-12 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($src)
        <div class="rounded-2xl overflow-hidden shadow-lg">
            <img src="{{ $src }}" alt="{{ $alt }}" class="w-full h-auto">
        </div>
        @else
        <div class="rounded-2xl overflow-hidden bg-gray-100 border-2 border-dashed border-gray-300 p-12 text-center">
            <p class="text-gray-400">Belum ada gambar</p>
        </div>
        @endif
        @if($caption)
        <p class="text-sm text-gray-500 mt-3 text-center">{{ $caption }}</p>
        @endif
    </div>
</section>
