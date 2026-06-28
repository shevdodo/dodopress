@props(['data' => []])
@php
    $items = $data['items'] ?? [];
@endphp

<section class="py-16 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-3">
            @foreach($items as $index => $item)
            <details class="group bg-gray-50 rounded-xl overflow-hidden border border-gray-100">
                <summary class="flex items-center justify-between p-5 cursor-pointer font-semibold text-gray-900 hover:bg-gray-100 transition">
                    <span>{{ $item['question'] ?? '' }}</span>
                    <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                <div class="px-5 pb-5 text-gray-600 leading-relaxed">
                    {{ $item['answer'] ?? '' }}
                </div>
            </details>
            @endforeach
        </div>
    </div>
</section>
