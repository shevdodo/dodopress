<section class="relative min-h-screen flex items-center overflow-hidden">
    <div class="absolute inset-0">
        @php
            $pos = $data['bg_position'] ?? 'center';
            $posClass = match($pos) {
                'top' => 'object-top',
                'bottom' => 'object-bottom',
                'left' => 'object-left',
                'right' => 'object-right',
                default => 'object-center',
            };
        @endphp
        @if(!empty($data['hero_bg']))
            <img src="{{ asset('storage/' . $data['hero_bg']) }}" alt="Hero" class="w-full h-full object-cover {{ $posClass }}" />
        @else
            <img src="{{ asset('storage/slider_bg.jpg') }}" alt="Hero" class="w-full h-full object-cover {{ $posClass }}" />
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
                        {{ $data['badge'] ?? 'Selamat Datang' }}
                    </span>
                </div>
                <h1 class="animate-fade-in-up stagger-2 text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-white leading-tight mb-6 tracking-tight">
                    {!! nl2br(e($data['title'] ?? "Bangun Website\nImpian Anda")) !!}
                </h1>
                <p class="animate-fade-in-up stagger-3 text-lg sm:text-xl text-gray-200 max-w-2xl mb-10 leading-relaxed">
                    {{ $data['subtitle'] ?? 'Platform CMS profesional yang dirancang untuk membantu Anda membangun dan mengelola website dengan mudah dan cepat.' }}
                </p>
                <div class="animate-fade-in-up stagger-4 flex flex-col sm:flex-row items-center gap-4">
                    <a href="{{ $data['cta_primary_link'] ?? '#' }}" class="group relative inline-flex items-center gap-2 px-8 py-4 bg-brand-600 text-white rounded-full text-lg font-bold hover:bg-brand-700 transition-all duration-300 shadow-2xl overflow-hidden">
                        <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></span>
                        {{ $data['cta_primary_text'] ?? 'Mulai Sekarang' }}
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </a>
                    <a href="{{ $data['cta_secondary_link'] ?? '#' }}" class="inline-flex items-center gap-2 px-8 py-4 border-2 border-white/30 text-white rounded-full text-lg font-bold hover:bg-white hover:text-gray-900 backdrop-blur-sm transition-all duration-300 group">
                        {{ $data['cta_secondary_text'] ?? 'Pelajari Lebih Lanjut' }}
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
                <div class="animate-fade-in-up stagger-5 mt-16 grid grid-cols-3 gap-8 max-w-lg">
                    @foreach($data['stats'] ?? [] as $stat)
                        <div><div class="text-3xl sm:text-4xl font-bold text-white">{{ $stat['number'] }}</div><div class="text-sm text-gray-300 mt-1">{{ $stat['label'] }}</div></div>
                    @endforeach
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
