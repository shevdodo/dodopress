@props(['data' => []])
@php
    $items = $data['items'] ?? [];
    $columns = $data['columns'] ?? 3;
    $gridClass = match((int)$columns) {
        2 => 'grid-cols-1 sm:grid-cols-2',
        4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
        default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
    };
@endphp

<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid {{ $gridClass }} gap-8">
            @foreach($items as $item)
            <div class="group p-6 rounded-2xl border border-gray-100 hover:border-brand-200 hover:shadow-lg transition-all duration-300">
                <div class="text-3xl mb-4">{{ $item['icon'] ?? '✨' }}</div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $item['title'] ?? '' }}</h3>
                <p class="text-gray-600 leading-relaxed">{{ $item['desc'] ?? '' }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
