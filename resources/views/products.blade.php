<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@php
    $siteTitle = \App\Models\Setting::where('key', 'site_title')->value('value') ?: config('app.name', 'Laravel');
    $favIcon = \App\Models\Setting::where('key', 'fav_icon')->value('value');
    $storeTitle = \App\Models\Setting::where('key', 'store_page_title')->value('value') ?: 'Our Products';
    $storeSubtitle = \App\Models\Setting::where('key', 'store_page_subtitle')->value('value');
    $storeBanner = \App\Models\Setting::where('key', 'store_banner_image')->value('value');
    $storeCatalogTitle = \App\Models\Setting::where('key', 'store_catalog_title')->value('value') ?: 'Katalog';

    if (isset($category)) {
        $metaDesc = $category->meta_description ?: 'Kategori produk ' . $category->name . ' di ' . $siteTitle;
        $metaImage = $category->image ? asset('storage/' . $category->image) : ($storeBanner ? asset('storage/' . $storeBanner) : null);
        
        \Artesaos\SEOTools\Facades\SEOMeta::setTitle($category->meta_title ?: $category->name . ' - ' . $siteTitle);
        \Artesaos\SEOTools\Facades\SEOMeta::setDescription($metaDesc);
        if ($category->meta_keywords) {
            \Artesaos\SEOTools\Facades\SEOMeta::addMeta('keywords', $category->meta_keywords);
        }

        \Artesaos\SEOTools\Facades\OpenGraph::setTitle($category->meta_title ?: $category->name . ' - ' . $siteTitle);
        \Artesaos\SEOTools\Facades\OpenGraph::setDescription($metaDesc);
        \Artesaos\SEOTools\Facades\OpenGraph::setUrl(url()->current());
        \Artesaos\SEOTools\Facades\OpenGraph::addProperty('type', 'website');
        if ($metaImage) {
            \Artesaos\SEOTools\Facades\OpenGraph::addImage($metaImage);
        }

        // Schema.org - BreadcrumbList
        \Artesaos\SEOTools\Facades\JsonLdMulti::newJsonLd();
        \Artesaos\SEOTools\Facades\JsonLdMulti::setType('BreadcrumbList');
        \Artesaos\SEOTools\Facades\JsonLdMulti::addValue('itemListElement', [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Home',
                'item' => url('/')
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'Store',
                'item' => route('product.index')
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $category->name,
                'item' => url()->current()
            ]
        ]);

        // Schema.org - CollectionPage & ItemList
        \Artesaos\SEOTools\Facades\JsonLdMulti::newJsonLd();
        \Artesaos\SEOTools\Facades\JsonLdMulti::setType('CollectionPage');
        \Artesaos\SEOTools\Facades\JsonLdMulti::setTitle($category->meta_title ?: $category->name);
        \Artesaos\SEOTools\Facades\JsonLdMulti::setDescription($metaDesc);
        \Artesaos\SEOTools\Facades\JsonLdMulti::setUrl(url()->current());
        
        $itemList = [];
        $position = 1;
        foreach ($products as $prod) {
            $itemList[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'url' => route('product.show', ['category_slug' => $category->slug, 'slug' => $prod->slug])
            ];
        }
        
        \Artesaos\SEOTools\Facades\JsonLdMulti::addValue('mainEntity', [
            '@type' => 'ItemList',
            'itemListElement' => $itemList
        ]);
    } else {
        // Fallback for main store page
        \Artesaos\SEOTools\Facades\SEOMeta::setTitle($storeTitle . ' - ' . $siteTitle);
        \Artesaos\SEOTools\Facades\SEOMeta::setDescription($storeSubtitle ?: 'Katalog produk dari ' . $siteTitle);
        \Artesaos\SEOTools\Facades\OpenGraph::setTitle($storeTitle . ' - ' . $siteTitle);
        \Artesaos\SEOTools\Facades\OpenGraph::setUrl(url()->current());
        \Artesaos\SEOTools\Facades\OpenGraph::addProperty('type', 'website');
        if ($storeBanner) {
            \Artesaos\SEOTools\Facades\OpenGraph::addImage(asset('storage/' . $storeBanner));
        }
    }
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! \Artesaos\SEOTools\Facades\SEOTools::generate() !!}
    
    @if(isset($category) && $category->meta_schema)
        {!! $category->meta_schema !!}
    @endif
    
    @if($favIcon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $favIcon) }}">
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <x-theme-config />
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50 min-h-screen flex flex-col">
    <x-frontend-navbar />

    @if(!isset($category) && $storeBanner)
        <div class="w-full h-64 md:h-80 lg:h-96 relative overflow-hidden">
            <img src="{{ asset('storage/' . $storeBanner) }}" alt="{{ $storeTitle }}" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4">
                <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 drop-shadow-md">{{ $storeTitle }}</h1>
                @if($storeSubtitle)
                    <p class="text-lg md:text-xl text-gray-100 max-w-2xl drop-shadow">{{ $storeSubtitle }}</p>
                @endif
            </div>
        </div>
    @endif

    <main class="flex-grow pt-8 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <h1 class="text-3xl font-extrabold text-gray-900">
                    @if(isset($category))
                        {{ $category->name }}
                    @else
                        {{ (!$storeBanner) ? $storeTitle : $storeCatalogTitle }}
                    @endif
                </h1>

                <!-- Search Form -->
                <form action="{{ isset($category) ? route('product.category', $category->slug) : route('product.index') }}" method="GET" class="flex w-full md:w-auto gap-2">
                    <div class="relative flex-grow md:w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all bg-white text-sm">
                        <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-2.5 rounded-xl font-semibold transition-colors text-sm shadow-sm whitespace-nowrap">
                        Search
                    </button>
                </form>
            </div>

            <!-- Categories Pill Filter -->
            @if(isset($categories) && $categories->count() > 0)
            <div class="flex flex-wrap gap-2 mb-8">
                <a href="{{ route('product.index') }}" class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors border {{ !isset($category) ? 'bg-brand-600 text-white border-brand-600 shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:border-brand-300 hover:text-brand-600' }}">All Products</a>
                @foreach($categories as $cat)
                    <a href="{{ route('product.category', $cat->slug) }}" class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors border {{ isset($category) && $category->id == $cat->id ? 'bg-brand-600 text-white border-brand-600 shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:border-brand-300 hover:text-brand-600' }}">{{ $cat->name }}</a>
                @endforeach
            </div>
            @endif
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2 sm:gap-4">
                @forelse($products as $product)
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col group hover:shadow-lg transition duration-300">
                    <a href="{{ route('product.show', ['category_slug' => $product->category ? $product->category->slug : 'uncategorized', 'slug' => $product->slug]) }}" class="relative block w-full aspect-square bg-gray-100 overflow-hidden">
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
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
                        @if($product->category)
                        <div class="mb-1 hidden sm:block">
                            <span class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wider line-clamp-1">{{ $product->category->name }}</span>
                        </div>
                        @endif
                        <h2 class="text-xs sm:text-sm font-medium text-gray-800 mb-1 leading-tight line-clamp-2">
                            <a href="{{ route('product.show', ['category_slug' => $product->category ? $product->category->slug : 'uncategorized', 'slug' => $product->slug]) }}" class="hover:text-brand-600 transition">{{ $product->name }}</a>
                        </h2>
                        <div class="mt-auto pt-1 sm:pt-2">
                            <span class="font-bold text-brand-600 text-sm sm:text-base">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                        @if($product->review_count > 0)
                        <div class="flex items-center gap-1 mt-1 sm:mt-2">
                            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-amber-400 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span class="text-[10px] sm:text-xs font-medium text-gray-500">{{ number_format($product->rating ?? 5.0, 1) }}</span>
                            <span class="text-[10px] sm:text-xs text-gray-400 border-l border-gray-300 pl-1 ml-1">{{ $product->review_count }} terjual</span>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-full py-16 text-center text-gray-500 bg-white rounded-2xl border border-gray-100">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    <p class="text-lg">No products available at the moment.</p>
                </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $products->links() }}
            </div>
        </div>
    </main>

    <x-frontend-footer />
</body>
</html>
