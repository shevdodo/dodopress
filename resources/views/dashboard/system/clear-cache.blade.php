<x-layouts.dashboard title="Clear Cache">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Clear System Cache</h2>
            <p class="text-sm text-gray-500 mt-1">
                Bersihkan cache aplikasi, tampilan (views), konfigurasi, dan rute.
            </p>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-medium flex items-center space-x-3">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden max-w-3xl">
        <div class="p-6 sm:p-8 border-b border-gray-100 bg-orange-50/50">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-orange-100 text-orange-600 rounded-xl shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Kenapa perlu Clear Cache?</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Jika Anda telah mengubah tampilan (tema), mengubah konfigurasi, atau memperbarui pengaturan website namun perubahan tersebut tidak langsung muncul/berpengaruh, kemungkinan besar sistem masih membaca versi cache lama. Menekan tombol di bawah ini akan memaksa sistem untuk membuat ulang cache terbaru.
                    </p>
                </div>
            </div>
        </div>
        <div class="p-6 sm:p-8">
            <form action="{{ route('superuser.clear-cache.execute') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg shadow-red-600/30 transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Bersihkan Semua Cache
                </button>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
