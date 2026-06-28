@php
    $limit = intval($data['limit'] ?? 3);
    $latestPosts = \App\Models\Post::where('status', 'published')->latest()->take($limit)->get();
@endphp

@if($latestPosts->count() > 0)
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-12 sm:mb-16">
            <div>
                <span class="text-brand-600 font-semibold text-sm tracking-widest uppercase">Blog</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mt-2">{{ $data['title'] ?? 'Artikel Terbaru' }}</h2>
                <div class="w-20 h-1 bg-gradient-to-r from-brand-400 to-brand-600 mt-4 rounded-full"></div>
            </div>
            <a href="{{ route('post.index') }}" class="hidden md:inline-flex items-center gap-2 mt-4 md:mt-0 px-6 py-3 border-2 border-brand-200 text-brand-700 rounded-full font-bold hover:bg-brand-50 hover:border-brand-300 transition-all duration-300 group">
                Lihat Semua <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($latestPosts as $index => $post)
                <article class="animate-fade-in-up stagger-{{ min($index + 1, 6) }} bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 border border-gray-100 group">
                    <a href="{{ route('post.show', ['category_slug' => $post->category ? $post->category->slug : 'uncategorized', 'slug' => $post->slug]) }}" class="block">
                        <div class="aspect-[16/9] bg-gray-100 overflow-hidden">
                            @if($post->thumbnail)
                                <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                                @if($post->category)
                                    <span class="px-3 py-1 bg-brand-50 text-brand-700 rounded-full text-xs font-semibold">{{ $post->category->name }}</span>
                                @endif
                                <span>{{ $post->created_at->format('d M Y') }}</span>
                            </div>
                            <h3 class="font-bold text-lg text-gray-900 line-clamp-2 group-hover:text-brand-600 transition-colors mb-3">{{ $post->title }}</h3>
                            <p class="text-gray-600 text-sm line-clamp-2 leading-relaxed">{{ Str::limit(strip_tags($post->body), 120) }}</p>
                            <div class="mt-4 text-brand-600 font-semibold text-sm inline-flex items-center gap-1 group-hover:gap-2 transition-all">
                                Baca Selengkapnya <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </div>
                        </div>
                    </a>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
