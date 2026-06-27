<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    $siteTitle = \App\Models\Setting::where('key', 'site_title')->value('value') ?: config('app.name', 'Laravel');
    $tagline = \App\Models\Setting::where('key', 'tagline')->value('value') ?: 'Platform profesional modern.';
    $favIcon = \App\Models\Setting::where('key', 'fav_icon')->value('value');
    $homePage = \App\Models\Page::where('slug', '__homepage__')->first();
    $homeContent = [];
    if ($homePage && !empty($homePage->content)) {
        $decoded = json_decode($homePage->content, true);
        if (json_last_error() === JSON_ERROR_NONE) { $homeContent = $decoded; }
    }
    $hc = function($key, $default = '') use ($homeContent) { return $homeContent[$key] ?? $default; };
    $featuredProducts = \App\Models\Product::where('status', 'available')->latest()->take(8)->get();
    $selectedCategoryIds = isset($homeContent['featured_category_ids']) && !empty($homeContent['featured_category_ids'])
        ? array_filter((array) $homeContent['featured_category_ids']) : [];
    if (!empty($selectedCategoryIds)) {
        $productCategories = \App\Models\Category::where('type', 'product')
            ->whereIn('id', $selectedCategoryIds)
            ->orderByRaw('FIELD(id, ' . implode(',', array_map('intval', $selectedCategoryIds)) . ')')->get();
    } else {
        $productCategories = \App\Models\Category::where('type', 'product')->take(4)->get();
    }
    $latestPosts = \App\Models\Post::where('status', 'published')->latest()->take(3)->get();
    $testimonials = [
        ['name' => 'Ahmad Fauzi', 'role' => 'Pengusaha', 'text' => 'Platform ini sangat membantu bisnis saya berkembang pesat!'],
        ['name' => 'Dewi Sartika', 'role' => 'Content Creator', 'text' => 'Sangat mudah digunakan, fiturnya lengkap dan supportnya cepat.'],
        ['name' => 'Budi Santoso', 'role' => 'Owner Toko Online', 'text' => 'CMS terbaik yang pernah saya gunakan. Sangat direkomendasikan!'],
    ];
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $tagline }}">
    <title>{{ $siteTitle }} - {{ $tagline }}</title>
    @if($favIcon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $favIcon) }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <x-theme-config />
    <style>
        @keyframes fadeInUp { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }
        @keyframes float { 0%,100% { transform:translateY(0); } 50% { transform:translateY(-10px); } }
        .animate-fade-in-up { animation:fadeInUp 0.8s ease-out forwards; }
        .animate-float { animation:float 4s ease-in-out infinite; }
        .stagger-1 { animation-delay:0.1s; } .stagger-2 { animation-delay:0.2s; }
        .stagger-3 { animation-delay:0.3s; } .stagger-4 { animation-delay:0.4s; }
        .stagger-5 { animation-delay:0.5s; } .stagger-6 { animation-delay:0.6s; }
        html { scroll-behavior:smooth; }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-white overflow-x-hidden">
    <x-frontend-navbar />

    <!-- ===== HERO SECTION ===== -->
    <section class="relative min-h-screen flex items-center overflow-hidden">
        <div class="absolute inset-0">
            @if(!empty($hc('hero_bg')))
                <img src="{{ asset('storage/' . $hc('hero_bg')) }}" alt="Hero" class="w-full h-full object-cover" />
            @else
                <img src="{{ asset('storage/slider_bg.jpg') }}" alt="Hero" class="w-full h-full object-cover" />
            @endif
            <div class="absolute inset-0 bg-gradient-to-r from-gray-900/80 via-gray-900/50 to-gray-900/30"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-transparent to-transparent"></div>
        </div>
        <div class="relative z-10 w-full">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-20 lg:pb-32">
                <div class="max-w-3xl">
                    <div class="animate-fade-in-up stagger-1">
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-sm font-semibold tracking-wider uppercase shadow-lg mb-8">
                            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                            {{ $hc('hero_badge', 'Selamat Datang') }}
                        </span>
                    </div>
                    <h1 class="animate-fade-in-up stagger-2 text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-white leading-tight mb-6 tracking-tight">
                        {!! nl2br(e($hc('hero_title', "Bangun Website\nImpian Anda"))) !!}
                    </h1>
                    <p class="animate-fade-in-up stagger-3 text-lg sm:text-xl text-gray-200 max-w-2xl mb-10 leading-relaxed">
                        {{ $hc('hero_subtitle', 'Platform CMS profesional yang dirancang untuk membantu Anda membangun dan mengelola website dengan mudah dan cepat.') }}
                    </p>
                    <div class="animate-fade-in-up stagger-4 flex flex-col sm:flex-row items-center gap-4">
                        <a href="{{ route('product.index') }}" class="group relative inline-flex items-center gap-2 px-8 py-4 bg-brand-600 text-white rounded-full text-lg font-bold hover:bg-brand-700 transition-all duration-300 shadow-2xl overflow-hidden">
                            <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></span>
                            {{ $hc('hero_cta_primary', 'Mulai Sekarang') }}
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </a>
                        <a href="{{ route('post.index') }}" class="inline-flex items-center gap-2 px-8 py-4 border-2 border-white/30 text-white rounded-full text-lg font-bold hover:bg-white hover:text-gray-900 backdrop-blur-sm transition-all duration-300 group">
                            {{ $hc('hero_cta_secondary', 'Pelajari Lebih Lanjut') }}
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                    <div class="animate-fade-in-up stagger-5 mt-16 grid grid-cols-3 gap-8 max-w-lg">
                        <div><div class="text-3xl sm:text-4xl font-bold text-white">10+</div><div class="text-sm text-gray-300 mt-1">Tahun Pengalaman</div></div>
                        <div><div class="text-3xl sm:text-4xl font-bold text-white">500+</div><div class="text-sm text-gray-300 mt-1">Klien Puas</div></div>
                        <div><div class="text-3xl sm:text-4xl font-bold text-white">99%</div><div class="text-sm text-gray-300 mt-1">Uptime</div></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 animate-float">
            <div class="w-6 h-10 border-2 border-white/30 rounded-full flex justify-center">
                <div class="w-1 h-3 bg-white/60 rounded-full mt-2 animate-bounce"></div>
            </div>
        </div>
    </section>


    <!-- ===== CATEGORIES SECTION ===== -->
    <section class="relative z-20 -mt-20 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-2xl p-8 sm:p-12">
                <div class="text-center mb-12">
                    <span class="text-brand-600 font-semibold text-sm tracking-widest uppercase">Kategori</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mt-2">{{ $hc('categories_title', 'Jelajahi Kategori') }}</h2>
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

    <!-- ===== FEATURED PRODUCTS ===== -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-12 sm:mb-16">
                <div>
                    <span class="text-brand-600 font-semibold text-sm tracking-widest uppercase">Produk & Layanan</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mt-2">{{ $hc('products_title', 'Produk Unggulan') }}</h2>
                    <div class="w-20 h-1 bg-gradient-to-r from-brand-400 to-brand-600 mt-4 rounded-full"></div>
                    <p class="mt-6 text-gray-600 max-w-xl text-lg">{{ $hc('products_subtitle', 'Temukan berbagai produk dan layanan terbaik kami.') }}</p>
                </div>
                <a href="{{ route('product.index') }}" class="hidden md:inline-flex items-center gap-2 mt-4 md:mt-0 px-6 py-3 border-2 border-brand-200 text-brand-700 rounded-full font-bold hover:bg-brand-50 hover:border-brand-300 transition-all duration-300 group">
                    Lihat Semua <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>
            <div class="hidden sm:grid sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                @foreach($featuredProducts as $index => $product)
                    <div class="product-card group bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-500 overflow-hidden flex flex-col border border-gray-100 hover:-translate-y-2 animate-fade-in-up stagger-{{ min($index + 1, 6) }}">
                        <div class="aspect-[4/5] overflow-hidden bg-gray-100 relative">
                            @if($product->images && count($product->images) > 0)
                                <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
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
                        </div>
                        <div class="p-4 sm:p-5 flex flex-col flex-1">
                            <h3 class="font-bold text-gray-900 text-base sm:text-lg line-clamp-2 min-h-[3rem]">
                                <a href="{{ route('product.show', ['category_slug' => $product->category ? $product->category->slug : 'uncategorized', 'slug' => $product->slug]) }}" class="hover:text-brand-600 transition">{{ $product->name }}</a>
                            </h3>
                            <div class="mt-auto pt-3 flex items-center justify-between">
                                <span class="font-extrabold text-brand-700 text-base sm:text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="text-gray-300 group-hover:text-brand-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="sm:hidden flex gap-4 overflow-x-auto pb-4 -mx-4 px-4 snap-x snap-mandatory">
                @foreach($featuredProducts as $product)
                    <a href="{{ route('product.show', ['category_slug' => $product->category ? $product->category->slug : 'uncategorized', 'slug' => $product->slug]) }}"
                       class="flex-shrink-0 w-64 bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100 snap-start">
                        <div class="aspect-[4/5] bg-gray-100">
                            @if($product->images && count($product->images) > 0)
                                <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" loading="lazy" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-gray-900 line-clamp-1">{{ $product->name }}</h3>
                            <span class="font-extrabold text-brand-700 text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-8 text-center sm:hidden">
                <a href="{{ route('product.index') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-brand-600 text-white rounded-full font-bold hover:bg-brand-700 transition">
                    Lihat Semua <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- ===== VALUE PROPOSITION ===== -->
    <section class="py-20 bg-brand-950 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-brand-500 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-brand-400 rounded-full blur-3xl"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-brand-400 font-semibold text-sm tracking-widest uppercase">Mengapa Memilih Kami</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-white mt-2">Kenapa {{ $siteTitle }}?</h2>
                <div class="w-20 h-1 bg-gradient-to-r from-brand-400 to-brand-600 mx-auto mt-4 rounded-full"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="text-center group">
                    <div class="w-20 h-20 mx-auto bg-gradient-to-br from-brand-500/20 to-brand-700/20 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 border border-brand-700/30">
                        <svg class="w-10 h-10 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4">{{ $hc('vp_title_1', 'Kualitas Terbaik') }}</h3>
                    <p class="text-brand-200 leading-relaxed">{{ $hc('vp_desc_1', 'Kami menggunakan teknologi dan standar terbaik untuk memberikan hasil yang optimal bagi Anda.') }}</p>
                </div>
                <div class="text-center group">
                    <div class="w-20 h-20 mx-auto bg-gradient-to-br from-brand-500/20 to-brand-700/20 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 border border-brand-700/30">
                        <svg class="w-10 h-10 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4">{{ $hc('vp_title_2', 'Harga Terjangkau') }}</h3>
                    <p class="text-brand-200 leading-relaxed">{{ $hc('vp_desc_2', 'Nikmati layanan berkualitas dengan harga yang kompetitif dan transparan tanpa biaya tersembunyi.') }}</p>
                </div>
                <div class="text-center group">
                    <div class="w-20 h-20 mx-auto bg-gradient-to-br from-brand-500/20 to-brand-700/20 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 border border-brand-700/30">
                        <svg class="w-10 h-10 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4">{{ $hc('vp_title_3', 'Dukungan 24/7') }}</h3>
                    <p class="text-brand-200 leading-relaxed">{{ $hc('vp_desc_3', 'Tim support profesional kami siap membantu Anda kapan pun Anda membutuhkannya.') }}</p>
                </div>
            </div>
        </div>
    </section>



    <!-- ===== TESTIMONIALS ===== -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-brand-600 font-semibold text-sm tracking-widest uppercase">Testimoni</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mt-2">Apa Kata Pelanggan Kami</h2>
                <div class="w-20 h-1 bg-gradient-to-r from-brand-400 to-brand-600 mx-auto mt-4 rounded-full"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($testimonials as $index => $testimonial)
                    <div class="animate-fade-in-up stagger-{{ min($index + 1, 6) }} bg-gray-50 rounded-2xl p-8 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            </div>
                        </div>
                        <p class="text-gray-600 leading-relaxed mb-6">"{{ $testimonial['text'] }}"</p>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($testimonial['name'], 0, 1) }}
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">{{ $testimonial['name'] }}</div>
                                <div class="text-sm text-gray-500">{{ $testimonial['role'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @if($latestPosts->count() > 0)
    <!-- ===== BLOG / ARTICLES ===== -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-12 sm:mb-16">
                <div>
                    <span class="text-brand-600 font-semibold text-sm tracking-widest uppercase">Blog</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mt-2">Artikel Terbaru</h2>
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



    <!-- ===== CTA / NEWSLETTER ===== -->
    <section class="py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-brand-800 to-brand-900"></div>
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-white rounded-full blur-3xl"></div>
        </div>
        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">Tetap Terhubung Dengan Kami</h2>
            <p class="text-lg text-brand-200 mb-10 max-w-2xl mx-auto">Berlangganan newsletter kami untuk mendapatkan informasi terbaru, tips, dan penawaran menarik.</p>
            <div class="flex flex-col sm:flex-row items-center gap-4 max-w-lg mx-auto">
                <input type="email" placeholder="Masukkan email Anda"
                    class="w-full px-6 py-4 rounded-full border-0 bg-white/10 backdrop-blur-sm text-white placeholder-brand-300 focus:outline-none focus:ring-2 focus:ring-white/30 text-base">
                <button class="w-full sm:w-auto px-8 py-4 bg-white text-brand-800 rounded-full font-bold hover:bg-brand-50 transition-all duration-300 shadow-xl whitespace-nowrap">
                    Berlangganan
                </button>
            </div>
            <p class="text-sm text-brand-300 mt-4">Tanpa spam. Berhenti berlangganan kapan saja.</p>
        </div>
    </section>


    <!-- ===== FOOTER ===== -->
    <x-frontend-footer />
</body>
</html>
