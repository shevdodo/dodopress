@props(['data' => []])
@php
    $content = $data['content'] ?? '';
@endphp

<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-lg prose-gray max-w-none">
            {!! $content !!}
        </div>
    </div>
</section>
