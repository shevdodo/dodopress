<section class="py-20 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-brand-800 to-brand-900"></div>
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-white rounded-full blur-3xl"></div>
    </div>
    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">{{ $data['title'] ?? 'Tetap Terhubung Dengan Kami' }}</h2>
        <p class="text-lg text-brand-200 mb-10 max-w-2xl mx-auto">{{ $data['subtitle'] ?? 'Berlangganan newsletter kami untuk mendapatkan informasi terbaru, tips, dan penawaran menarik.' }}</p>
        <div class="flex flex-col sm:flex-row items-center gap-4 max-w-lg mx-auto">
            <input type="email" placeholder="Masukkan email Anda"
                class="w-full px-6 py-4 rounded-full border-0 bg-white/10 backdrop-blur-sm text-white placeholder-brand-300 focus:outline-none focus:ring-2 focus:ring-white/30 text-base">
            <button class="w-full sm:w-auto px-8 py-4 bg-white text-brand-800 rounded-full font-bold hover:bg-brand-50 transition-all duration-300 shadow-xl whitespace-nowrap">
                {{ $data['button_text'] ?? 'Berlangganan' }}
            </button>
        </div>
        <p class="text-sm text-brand-300 mt-4">Tanpa spam. Berhenti berlangganan kapan saja.</p>
    </div>
</section>
