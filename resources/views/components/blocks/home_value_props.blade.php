<section class="py-20 bg-brand-950 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-brand-500 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-brand-400 rounded-full blur-3xl"></div>
    </div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-brand-400 font-semibold text-sm tracking-widest uppercase">{{ $data['subtitle'] ?? 'Mengapa Memilih Kami' }}</span>
            <h2 class="text-3xl sm:text-4xl font-bold text-white mt-2">{{ $data['title'] ?? 'Kenapa Kami?' }}</h2>
            <div class="w-20 h-1 bg-gradient-to-r from-brand-400 to-brand-600 mx-auto mt-4 rounded-full"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            @foreach($data['props'] ?? [] as $index => $prop)
            <div class="text-center group">
                <div class="w-20 h-20 mx-auto bg-gradient-to-br from-brand-500/20 to-brand-700/20 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 border border-brand-700/30">
                    @if($index == 0)
                    <svg class="w-10 h-10 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                    @elseif($index == 1)
                    <svg class="w-10 h-10 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    @else
                    <svg class="w-10 h-10 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    @endif
                </div>
                <h3 class="text-xl font-bold mb-4">{{ $prop['title'] }}</h3>
                <p class="text-brand-200 leading-relaxed">{{ $prop['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
