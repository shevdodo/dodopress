@props(['data' => []])
@php
    $badge         = $data['badge'] ?? 'Tentang Kami';
    $title         = $data['title'] ?? 'Mengenal Lebih Dekat';
    $subtitle      = $data['subtitle'] ?? 'Kami berdedikasi untuk memberikan solusi terbaik dan inovatif bagi kebutuhan digital Anda.';
    $hero_bg       = $data['hero_bg'] ?? '';
    
    $story_title   = $data['story_title'] ?? 'Cerita Perjalanan Kami';
    $story_content = $data['story_content'] ?? 'Berawal dari visi sederhana, kami terus berkembang dan berinovasi. Tim kami terdiri dari para ahli yang bersemangat untuk menciptakan produk yang berdampak positif.';
    $story_image   = $data['story_image'] ?? '';
    
    $vision_title  = $data['vision_title'] ?? 'Visi Kami';
    $vision_text   = $data['vision_text'] ?? 'Menjadi pelopor inovasi digital yang memberikan nilai tambah bagi masyarakat dan bisnis di seluruh dunia.';
    
    $mission_title = $data['mission_title'] ?? 'Misi Kami';
    $mission_text  = $data['mission_text'] ?? 'Mengembangkan produk berkualitas, membangun kolaborasi yang kuat, dan terus beradaptasi dengan teknologi terbaru.';
    
    $stats = $data['stats'] ?? [
        ['number' => '10+', 'label' => 'Tahun Pengalaman'],
        ['number' => '500+', 'label' => 'Klien Bahagia'],
        ['number' => '50+', 'label' => 'Anggota Tim'],
    ];
@endphp

{{-- ===== HERO ===== --}}
<section class="relative overflow-hidden bg-gradient-to-br from-brand-900 via-gray-900 to-gray-950 text-white pt-40 pb-24 lg:pt-48 lg:pb-36">
    @if($hero_bg)
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20 mix-blend-overlay" style="background-image: url('{{ asset('storage/'.$hero_bg) }}')"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-gray-900/60 to-gray-950"></div>
    @else
    <div class="absolute -top-40 -right-40 w-[520px] h-[520px] bg-brand-600/20 rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute -bottom-32 -left-20 w-[400px] h-[400px] bg-brand-700/20 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute inset-0 opacity-[0.03]" style="background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:32px 32px;"></div>
    @endif

    <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        @if($badge)
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-brand-500/20 border border-brand-500/30 text-brand-300 text-sm font-medium mb-8 backdrop-blur-sm">
            <span class="w-1.5 h-1.5 rounded-full bg-brand-400"></span>
            {{ $badge }}
        </div>
        @endif
        <h1 class="text-4xl sm:text-5xl lg:text-7xl font-extrabold leading-tight mb-8 tracking-tight">
            {!! nl2br(e($title)) !!}
        </h1>
        @if($subtitle)
        <p class="text-lg sm:text-xl text-gray-300/90 max-w-3xl mx-auto leading-relaxed">{{ $subtitle }}</p>
        @endif
    </div>
</section>

{{-- ===== STORY SECTION ===== --}}
<section class="py-20 lg:py-32 bg-white relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="order-2 lg:order-1 relative">
                @if($story_image)
                    <div class="rounded-3xl overflow-hidden shadow-2xl relative z-10">
                        <img src="{{ asset('storage/'.$story_image) }}" alt="Our Story" class="w-full h-auto object-cover aspect-[4/5] lg:aspect-auto">
                    </div>
                @else
                    <div class="rounded-3xl overflow-hidden shadow-2xl relative z-10 bg-gradient-to-tr from-brand-100 to-gray-100 aspect-[4/5] flex items-center justify-center">
                        <svg class="w-24 h-24 text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                @endif
                <div class="absolute -bottom-6 -left-6 w-48 h-48 bg-brand-100 rounded-full blur-[60px] -z-10"></div>
                <div class="absolute -top-6 -right-6 w-48 h-48 bg-brand-50 rounded-full blur-[60px] -z-10"></div>
            </div>
            
            <div class="order-1 lg:order-2">
                <div class="mb-4 flex items-center gap-3">
                    <div class="w-10 h-px bg-brand-500"></div>
                    <span class="text-brand-600 font-bold uppercase tracking-widest text-sm">Our Story</span>
                </div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-8 leading-tight">{{ $story_title }}</h2>
                <div class="prose prose-lg prose-brand text-gray-600">
                    {!! $story_content !!}
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== STATS ===== --}}
@if(is_array($stats) && count($stats) > 0)
<section class="py-16 bg-brand-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-{{ min(count($stats), 4) }} gap-8 text-center divide-x divide-brand-400/30">
            @foreach($stats as $stat)
                <div class="px-4">
                    <p class="text-4xl sm:text-5xl font-extrabold text-white mb-2">{{ $stat['number'] ?? '' }}</p>
                    <p class="text-brand-100 font-medium tracking-wide uppercase text-sm">{{ $stat['label'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ===== VISION & MISSION ===== --}}
<section class="py-20 lg:py-32 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-16">
            
            {{-- Vision Card --}}
            <div class="bg-white rounded-3xl p-10 lg:p-12 shadow-xl shadow-gray-200/50 border border-gray-100 hover:-translate-y-2 transition-transform duration-300">
                <div class="w-16 h-16 bg-brand-50 rounded-2xl flex items-center justify-center mb-8 text-brand-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $vision_title }}</h3>
                <p class="text-gray-600 leading-relaxed text-lg">{{ $vision_text }}</p>
            </div>

            {{-- Mission Card --}}
            <div class="bg-gradient-to-br from-brand-900 to-gray-900 rounded-3xl p-10 lg:p-12 shadow-xl shadow-brand-900/20 text-white hover:-translate-y-2 transition-transform duration-300 relative overflow-hidden">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-brand-600/30 rounded-full blur-[80px]"></div>
                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mb-8 text-brand-300 relative z-10 backdrop-blur-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="text-2xl font-bold mb-4 relative z-10">{{ $mission_title }}</h3>
                <p class="text-brand-100 leading-relaxed text-lg relative z-10">{{ $mission_text }}</p>
            </div>

        </div>
    </div>
</section>
