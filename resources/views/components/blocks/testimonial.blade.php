@props(['data' => []])
@php
    $items = $data['items'] ?? [];
@endphp

<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($items as $item)
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <svg class="w-8 h-8 text-brand-300 mb-4" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                <p class="text-gray-600 mb-4 leading-relaxed italic">"{{ $item['quote'] ?? '' }}"</p>
                <div class="flex items-center gap-3">
                    @if(!empty($item['avatar']))
                    <img src="{{ $item['avatar'] }}" alt="" class="w-10 h-10 rounded-full object-cover">
                    @else
                    <div class="w-10 h-10 rounded-full bg-brand-100 flex items-center justify-center text-brand-600 font-bold text-sm">
                        {{ strtoupper(substr($item['name'] ?? 'U', 0, 1)) }}
                    </div>
                    @endif
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">{{ $item['name'] ?? '' }}</p>
                        <p class="text-xs text-gray-500">{{ $item['role'] ?? '' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
