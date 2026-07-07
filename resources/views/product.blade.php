<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@php
    $siteTitle = \App\Models\Setting::where('key', 'site_title')->value('value') ?: config('app.name', 'Laravel');
    $favIcon = \App\Models\Setting::where('key', 'fav_icon')->value('value');
    $siteLogo = \App\Models\Setting::where('key', 'site_logo')->value('value');
    $metaDesc = \Illuminate\Support\Str::limit(strip_tags($product->description), 150);
    
    // Determine the product image (handle single image or image array)
    $prodImg = $product->image;
    if (empty($prodImg) && !empty($product->images) && is_array($product->images) && count($product->images) > 0) {
        $prodImg = $product->images[0];
    }
    
    $whatsappCs = \App\Models\Setting::where('key', 'whatsapp_cs_number')->value('value') ?: '6281329515082';
    
    $metaImage = $prodImg ? asset('storage/' . $prodImg) : ($siteLogo ? asset('storage/' . $siteLogo) : ($favIcon ? asset('storage/' . $favIcon) : ''));

    \Artesaos\SEOTools\Facades\SEOMeta::setTitle($product->meta_title ?: $product->name . ' - ' . $siteTitle);
    \Artesaos\SEOTools\Facades\SEOMeta::setDescription($product->meta_description ?: $metaDesc);
    if ($product->meta_keywords) {
        \Artesaos\SEOTools\Facades\SEOMeta::addMeta('keywords', $product->meta_keywords);
    }
    
    \Artesaos\SEOTools\Facades\OpenGraph::setTitle($product->meta_title ?: $product->name . ' - ' . $siteTitle);
    \Artesaos\SEOTools\Facades\OpenGraph::setDescription($product->meta_description ?: $metaDesc);
    \Artesaos\SEOTools\Facades\OpenGraph::setUrl(url()->current());
    \Artesaos\SEOTools\Facades\OpenGraph::addProperty('type', 'product');
    \Artesaos\SEOTools\Facades\OpenGraph::addProperty('product:price:amount', $product->price);
    \Artesaos\SEOTools\Facades\OpenGraph::addProperty('product:price:currency', 'IDR');
    if ($metaImage) {
        \Artesaos\SEOTools\Facades\OpenGraph::addImage($metaImage);
    }

    \Artesaos\SEOTools\Facades\JsonLdMulti::setTitle($product->meta_title ?: $product->name . ' - ' . $siteTitle);
    \Artesaos\SEOTools\Facades\JsonLdMulti::setDescription($product->meta_description ?: $metaDesc);
    \Artesaos\SEOTools\Facades\JsonLdMulti::setType('Product');
    \Artesaos\SEOTools\Facades\JsonLdMulti::addValue('offers', [
        '@type' => 'Offer',
        'price' => $product->price,
        'priceCurrency' => 'IDR',
        'availability' => $product->status === 'available' ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock'
    ]);
    
    if ($product->review_count > 0) {
        \Artesaos\SEOTools\Facades\JsonLdMulti::addValue('aggregateRating', [
            '@type' => 'AggregateRating',
            'ratingValue' => $product->rating ?? 5.0,
            'reviewCount' => $product->review_count,
        ]);
        
        \Artesaos\SEOTools\Facades\JsonLdMulti::addValue('review', [
            '@type' => 'Review',
            'reviewRating' => [
                '@type' => 'Rating',
                'ratingValue' => $product->rating ?? 5.0,
                'bestRating' => '5',
            ],
            'author' => [
                '@type' => 'Person',
                'name' => 'Customer',
            ],
        ]);
    }
    if ($metaImage) {
        \Artesaos\SEOTools\Facades\JsonLdMulti::addImage($metaImage);
    }
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! \Artesaos\SEOTools\Facades\SEOTools::generate() !!}
    @if($product->meta_schema)
        {!! $product->meta_schema !!}
    @endif

    @if($favIcon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $favIcon) }}">
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <x-theme-config />
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50 min-h-screen flex flex-col" x-data="{ lightboxOpen: false, activeLightboxImage: '' }">
    <x-frontend-navbar />

    <main class="flex-grow pt-8 pb-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row">
                <div class="w-full md:w-5/12 p-4 sm:p-6 lg:p-8 flex items-start justify-center bg-gray-50/50">
                    @php
                        $allImages = [];
                        if($product->image) $allImages[] = $product->image;
                        if(!empty($product->images) && is_array($product->images)) {
                            foreach($product->images as $img) {
                                $allImages[] = $img;
                            }
                        }
                    @endphp
                    
                    @if(count($allImages) > 0)
                        <div x-data="{ mainImage: '{{ asset('storage/' . $allImages[0]) }}' }" class="w-full max-w-md flex flex-col gap-4">
                            <div class="cursor-pointer group relative w-full aspect-square" @click="activeLightboxImage = mainImage; lightboxOpen = true">
                                <img :src="mainImage" alt="{{ $product->name }}" class="w-full h-full rounded-2xl shadow-lg object-cover transition transform group-hover:scale-[1.02] duration-300">
                                <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl flex items-center justify-center">
                                    <div class="bg-white/90 p-3 rounded-full text-gray-800 shadow-xl transform scale-90 group-hover:scale-100 transition-transform duration-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                                    </div>
                                </div>
                            </div>
                            
                            @if(count($allImages) > 1)
                                <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide">
                                    @foreach($allImages as $img)
                                        <button @click="mainImage = '{{ asset('storage/' . $img) }}'" 
                                                :class="mainImage === '{{ asset('storage/' . $img) }}' ? 'border-brand-500 ring-2 ring-brand-500/50' : 'border-transparent opacity-70 hover:opacity-100'"
                                                class="flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden border-2 transition-all">
                                            <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="w-full max-w-md aspect-square bg-gray-200 rounded-2xl flex items-center justify-center">
                            <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                    @endif
                </div>
                
                <div class="w-full md:w-7/12 p-6 sm:p-8 lg:p-10 flex flex-col">
                    <!-- Breadcrumbs (Map Slug) -->
                    <nav class="flex items-center text-xs sm:text-sm text-gray-500 uppercase tracking-widest font-semibold mb-4 space-x-2">
                        <a href="{{ url('/') }}" class="hover:text-brand-600 transition">Home</a>
                        <span class="text-gray-300">/</span>
                        <a href="{{ route('product.index') }}" class="hover:text-brand-600 transition">Store</a>
                        @if($product->category)
                        <span class="text-gray-300">/</span>
                        <a href="{{ route('product.category', $product->category->slug) }}" class="text-gray-900 hover:text-brand-600 transition">{{ $product->category->name }}</a>
                        @endif
                    </nav>
                    
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-2">{{ $product->name }}</h1>
                    
                    @if($product->review_count > 0)
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex items-center text-amber-400">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= round($product->rating ?? 5.0) ? 'fill-current' : 'text-gray-200 fill-current' }}" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <span class="text-sm font-medium text-gray-600">{{ number_format($product->rating ?? 5.0, 1) }}</span>
                        <span class="text-sm text-gray-400">({{ $product->review_count }} ulasan)</span>
                    </div>
                    @endif
                    
                    {{-- Price, Weight, and Stock details --}}
                    <div class="mb-6 flex flex-col gap-2">
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-brand-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex items-center gap-3 flex-wrap mt-2">
                            @if($product->weight)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" /></svg>
                                    {{ $product->weight }} gr
                                </span>
                            @endif

                            @if($product->stock > 0)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Ready Stock ({{ $product->stock }})
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    Stok Habis
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Removed description from here --}}
                    
                    <div>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            
                            @php
                                $sizeList = [];
                                if (!empty($product->sizes)) {
                                    $sizeList = array_filter(array_map('trim', explode(',', $product->sizes)));
                                }
                            @endphp

                            @if(!empty($sizeList))
                                <div class="mb-6 flex items-center gap-4" x-data="{ selectedSize: '{{ reset($sizeList) }}' }">
                                    <label class="w-24 text-sm font-semibold text-gray-600">Ukuran</label>
                                    <div class="flex flex-wrap gap-2">
                                        <input type="hidden" name="size" :value="selectedSize">
                                        @foreach($sizeList as $size)
                                            <button type="button" @click="selectedSize = '{{ $size }}'"
                                                :class="selectedSize === '{{ $size }}' ? 'bg-brand-50 text-brand-700 border-brand-500' : 'bg-white text-gray-700 border-gray-300 hover:border-gray-400 hover:bg-gray-50'"
                                                class="min-w-[3rem] px-3 py-1.5 text-sm font-medium border rounded transition duration-150">
                                                {{ $size }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="mb-8 flex items-center gap-4" x-data="{ qty: 1 }">
                                <label class="w-24 text-sm font-semibold text-gray-600">Kuantitas</label>
                                <div class="flex items-center border border-gray-300 rounded overflow-hidden">
                                    <button type="button" @click="if(qty > 1) qty--" class="px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-600 border-r border-gray-300 transition focus:outline-none">&minus;</button>
                                    <input type="number" name="quantity" x-model="qty" min="1" max="{{ $product->stock > 0 ? $product->stock : 1 }}" class="w-14 text-center py-1.5 border-0 focus:ring-0 text-sm font-medium text-gray-800" readonly>
                                    <button type="button" @click="if(qty < {{ $product->stock > 0 ? $product->stock : 1 }}) qty++" class="px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-600 border-l border-gray-300 transition focus:outline-none">&plus;</button>
                                </div>
                                @if($product->stock > 0)
                                    <span class="text-sm text-gray-500 ml-2">Tersisa {{ $product->stock }} buah</span>
                                @endif
                            </div>

                            <div class="flex flex-col sm:flex-row gap-4">
                                <button type="submit" 
                                    @if($product->stock <= 0) disabled @endif
                                    class="w-full sm:w-1/2 px-6 py-3.5 text-brand-600 font-bold rounded-lg border border-brand-600 bg-brand-50 hover:bg-brand-100 transition disabled:bg-gray-100 disabled:text-gray-400 disabled:border-gray-300 disabled:cursor-not-allowed">
                                    @if($product->stock <= 0)
                                        Stok Habis
                                    @else
                                        <span class="flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            Masukkan Keranjang
                                        </span>
                                    @endif
                                </button>
                                
                                @php
                                    $waMessage = "Halo CS Batik Mukti, saya tertarik dengan produk *" . $product->name . "*. Apakah masih tersedia?\n\nLink Produk: " . route('product.show', ['category_slug' => $product->category ? $product->category->slug : 'uncategorized', 'slug' => $product->slug]);
                                    $waUrl = "https://api.whatsapp.com/send?phone=" . $whatsappCs . "&text=" . urlencode($waMessage);
                                @endphp
                                
                                <a href="{{ $waUrl }}" target="_blank"
                                    class="w-full sm:w-1/2 px-6 py-3.5 text-white font-bold rounded-lg bg-[#25D366] hover:bg-[#128C7E] shadow-md transition flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.029 18.88c-1.161 0-2.305-.292-3.318-.844l-3.677.964.984-3.595c-.607-1.052-.927-2.246-.926-3.468.001-3.825 3.113-6.937 6.937-6.937 3.82 0 6.935 3.115 6.936 6.938-.001 3.82-3.116 6.935-6.936 6.942z"/></svg>
                                    Tanya CS
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Description Card -->
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-10">
                <h2 class="text-xl font-bold text-gray-800 mb-6 border-b border-gray-100 pb-4">Spesifikasi & Deskripsi Produk</h2>
                <div class="prose max-w-none text-gray-600 leading-relaxed text-sm sm:text-base">
                    {!! $product->description !!}
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-700 mb-8 uppercase tracking-wider border-b border-gray-200 pb-3">Related Products</h2>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-8">
                @foreach($relatedProducts as $relProduct)
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col group hover:shadow-lg transition duration-300">
                    <a href="{{ route('product.show', ['category_slug' => $relProduct->category ? $relProduct->category->slug : 'uncategorized', 'slug' => $relProduct->slug]) }}" class="relative block w-full aspect-square bg-gray-100 overflow-hidden">
                        @if($relProduct->image)
                        <img src="{{ asset('storage/' . $relProduct->image) }}" alt="{{ $relProduct->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-8 h-8 sm:w-12 sm:h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        @endif
                        
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center hidden sm:flex">
                            <span class="px-6 py-2 bg-white text-gray-900 font-bold rounded-full shadow-lg transform -translate-y-4 group-hover:translate-y-0 transition duration-300">View Details</span>
                        </div>
                    </a>
                    
                    <div class="p-3 sm:p-5 flex-grow flex flex-col">
                        <div class="mb-1">
                            <span class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wider line-clamp-1">{{ $relProduct->category ? $relProduct->category->name : 'Uncategorized' }}</span>
                        </div>
                        <h3 class="text-sm sm:text-lg font-bold text-gray-900 mb-1 sm:mb-2 leading-tight line-clamp-2">
                            <a href="{{ route('product.show', ['category_slug' => $relProduct->category ? $relProduct->category->slug : 'uncategorized', 'slug' => $relProduct->slug]) }}" class="hover:text-brand-600 transition">{{ $relProduct->name }}</a>
                        </h3>
                        <div class="mt-auto">
                            <span class="text-sm sm:text-lg font-extrabold text-brand-600">Rp {{ number_format($relProduct->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </main>

    <x-frontend-footer />

    <!-- Lightbox Modal -->
    <div x-show="lightboxOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 backdrop-blur-sm" x-transition.opacity>
        <button @click="lightboxOpen = false" class="absolute top-6 right-6 text-white/70 hover:text-white transition z-10 bg-black/50 p-2 rounded-full">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
        <img :src="activeLightboxImage" alt="{{ $product->name }}" class="max-w-[90vw] max-h-[90vh] object-contain shadow-2xl" @click.away="lightboxOpen = false">
        <div class="absolute bottom-6 left-0 right-0 text-center pointer-events-none">
            <p class="text-white/80 text-lg font-medium tracking-wider drop-shadow-md">{{ $product->name }}</p>
        </div>
    </div>
</body>
</html>
