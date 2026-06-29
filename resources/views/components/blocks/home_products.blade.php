@php
    $limit = intval($data['limit'] ?? 8);
    $featuredProducts = \App\Models\Product::where('status', 'available')->latest()->take($limit)->get();
@endphp

<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-12 sm:mb-16">
            <div>
                <span class="text-brand-600 font-semibold text-sm tracking-widest uppercase">Produk & Layanan</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mt-2">{{ $data['title'] ?? 'Produk Unggulan' }}</h2>
                <div class="w-20 h-1 bg-gradient-to-r from-brand-400 to-brand-600 mt-4 rounded-full"></div>
                <p class="mt-6 text-gray-600 max-w-xl text-lg">{{ $data['subtitle'] ?? 'Temukan berbagai produk dan layanan terbaik kami.' }}</p>
            </div>
            <a href="{{ route('product.index') }}" class="hidden md:inline-flex items-center gap-2 mt-4 md:mt-0 px-6 py-3 border-2 border-brand-200 text-brand-700 rounded-full font-bold hover:bg-brand-50 hover:border-brand-300 transition-all duration-300 group">
                Lihat Semua <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 lg:gap-8">
            @foreach($featuredProducts as $index => $product)
                <div class="product-card group bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-500 overflow-hidden flex flex-col border border-gray-100 hover:-translate-y-2 animate-fade-in-up stagger-{{ min($index + 1, 6) }}">
                    <a href="{{ route('product.show', ['category_slug' => $product->category ? $product->category->slug : 'uncategorized', 'slug' => $product->slug]) }}" class="block aspect-[4/5] overflow-hidden bg-gray-100 relative">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                        @endif

                        @if($product->category)
                            <span class="absolute top-3 left-3 px-3 py-1.5 bg-white/90 backdrop-blur-sm text-xs font-bold text-gray-700 rounded-full shadow-sm">{{ $product->category->name }}</span>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4 text-white opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-4 group-hover:translate-y-0">
                            <span class="text-sm font-bold inline-flex items-center gap-1">Lihat Detail <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg></span>
                        </div>
                    </a>
                    <div class="p-4 sm:p-5 flex flex-col flex-1">
                        <h3 class="font-bold text-gray-900 text-base sm:text-lg line-clamp-2 min-h-[3rem]">
                            <a href="{{ route('product.show', ['category_slug' => $product->category ? $product->category->slug : 'uncategorized', 'slug' => $product->slug]) }}" class="hover:text-brand-600 transition">{{ $product->name }}</a>
                        </h3>
                        <div class="mt-auto pt-3 flex items-center justify-between">
                            <span class="font-extrabold text-brand-700 text-sm sm:text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <span class="text-gray-300 group-hover:text-brand-500 transition-colors hidden sm:block">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 text-center sm:hidden">
            <a href="{{ route('product.index') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-brand-600 text-white rounded-full font-bold hover:bg-brand-700 transition">
                Lihat Semua <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
            </a>
        </div>
    </div>
</section>
