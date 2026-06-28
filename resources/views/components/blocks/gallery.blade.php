@props(['data' => []])
@php
    $images = $data['images'] ?? [];
    $columns = $data['columns'] ?? 3;
    $gridClass = match((int)$columns) {
        2 => 'grid-cols-1 sm:grid-cols-2',
        4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
        default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
    };
@endphp

<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(count($images) > 0)
        <div class="grid {{ $gridClass }} gap-4">
            @foreach($images as $img)
            <div class="rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                <img src="{{ $img }}" alt="" class="w-full h-48 sm:h-56 object-cover">
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12 bg-white rounded-2xl border-2 border-dashed border-gray-200">
            <p class="text-gray-400">Belum ada gambar galeri</p>
        </div>
        @endif
    </div>
</section>
