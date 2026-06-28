@php
    $selectedCategoryIds = isset($data['category_ids']) && !empty($data['category_ids'])
        ? array_filter(array_map('trim', explode(',', $data['category_ids']))) : [];
    
    if (!empty($selectedCategoryIds)) {
        $productCategories = \App\Models\Category::where('type', 'product')
            ->whereIn('id', $selectedCategoryIds)
            ->orderByRaw('FIELD(id, ' . implode(',', array_map('intval', $selectedCategoryIds)) . ')')
            ->get();
    } else {
        $productCategories = \App\Models\Category::where('type', 'product')->take(4)->get();
    }
@endphp

<section class="relative z-20 -mt-20 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-2xl p-8 sm:p-12">
            <div class="text-center mb-12">
                <span class="text-brand-600 font-semibold text-sm tracking-widest uppercase">Kategori</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mt-2">{{ $data['title'] ?? 'Jelajahi Kategori' }}</h2>
                <div class="w-20 h-1 bg-gradient-to-r from-brand-400 to-brand-600 mx-auto mt-4 rounded-full"></div>
            </div>
            @php
                $catCount = $productCategories->count();
                $gridClass = match(true) {
                    $catCount === 1 => 'grid-cols-1 max-w-xs mx-auto',
                    $catCount === 2 => 'grid-cols-2 max-w-lg mx-auto',
                    $catCount === 3 => 'grid-cols-3 max-w-3xl mx-auto',
                    default         => 'grid-cols-2 md:grid-cols-4',
                };
            @endphp
            <div class="grid {{ $gridClass }} gap-6 sm:gap-8 justify-items-center">
                @foreach($productCategories as $index => $cat)
                    <a href="{{ route('product.category', $cat->slug) }}"
                       class="group block text-center animate-fade-in-up stagger-{{ min($index + 1, 6) }}">
                        <div class="relative w-28 h-28 sm:w-36 sm:h-36 mx-auto rounded-2xl overflow-hidden mb-4 shadow-lg group-hover:shadow-2xl transition-all duration-500">
                            @if($cat->image)
                                <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            @else
                                <img src="https://picsum.photos/300/300?random={{ $cat->id }}" alt="{{ $cat->name }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-brand-600/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-3 text-white text-center opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0">
                                <span class="text-sm font-bold">Lihat →</span>
                            </div>
                        </div>
                        <span class="font-bold text-gray-800 group-hover:text-brand-600 transition-colors text-base sm:text-lg">{{ $cat->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
