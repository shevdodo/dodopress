<x-layouts.dashboard title="Header Settings">
    <div class="max-w-4xl mx-auto">
        <!-- Page Title -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center gap-3">
                    <div class="p-2 bg-brand-100 text-brand-700 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    Header Settings &amp; Scripts
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola script header seperti Google Tag Manager, Google Analytics, Meta Pixel, atau custom kode HTML/JS di website Anda.</p>
            </div>
        </div>

        @if (session('status'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('superuser.settings.header.update') }}" method="POST" class="p-6 sm:p-8 space-y-8">
                @csrf

                <!-- Quick Preset Helper Box -->
                <div class="p-4 rounded-xl bg-indigo-50/60 border border-indigo-100 text-indigo-950 text-xs sm:text-sm space-y-2">
                    <div class="flex items-center gap-2 font-bold text-indigo-900">
                        <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Panduan Penggunaan Header Scripts</span>
                    </div>
                    <p class="text-indigo-800/80 leading-relaxed">
                        Kode yang disisipkan di halaman ini akan otomatis dimuat di semua halaman pengunjung (frontend). Anda bisa menambahkan kode tracking seperti <strong>Google Tag Manager (GTM)</strong>, <strong>Google Analytics 4 (GA4)</strong>, <strong>Meta Pixel (Facebook Pixel)</strong>, custom tag <strong>&lt;meta&gt;</strong>, atau <strong>&lt;style&gt;</strong> CSS.
                    </p>
                </div>

                <!-- Section 1: Scripts in <head> -->
                <div>
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-gray-100">
                        <label for="header_scripts" class="block text-base font-bold text-gray-900">
                            Header Scripts <span class="text-gray-400 font-normal text-xs sm:text-sm">(Sisipkan di dalam tag &lt;head&gt;)</span>
                        </label>
                        <span class="text-xs font-mono bg-gray-100 text-gray-600 px-2 py-0.5 rounded-md">&lt;head&gt; ... &lt;/head&gt;</span>
                    </div>
                    <p class="text-xs text-gray-500 mb-3">
                        Masukkan script JavaScript utama dari Google Tag Manager, Google Analytics, Meta Pixel, atau custom style CSS di sini.
                    </p>
                    <div class="relative">
                        <textarea id="header_scripts" name="header_scripts" rows="10" 
                            placeholder="<!-- Contoh Google Tag Manager / Analytics / Meta Pixel -->&#10;<script async src=&quot;https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX&quot;></script>&#10;<script>&#10;  window.dataLayer = window.dataLayer || [];&#10;  function gtag(){dataLayer.push(arguments);}&#10;  gtag('js', new Date());&#10;  gtag('config', 'G-XXXXXXXXXX');&#10;</script>"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-brand-500 transition-colors bg-slate-900 text-slate-100 font-mono text-xs leading-relaxed focus:outline-none placeholder-slate-500">{{ old('header_scripts', $settings['header_scripts'] ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Section 2: Scripts in Body Top -->
                <div>
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-gray-100">
                        <label for="header_body_scripts" class="block text-base font-bold text-gray-900">
                            Body Top Scripts <span class="text-gray-400 font-normal text-xs sm:text-sm">(Sisipkan setelah pembuka tag &lt;body&gt;)</span>
                        </label>
                        <span class="text-xs font-mono bg-gray-100 text-gray-600 px-2 py-0.5 rounded-md">&lt;body&gt; ... &lt;/body&gt;</span>
                    </div>
                    <p class="text-xs text-gray-500 mb-3">
                        Masukkan script sekunder seperti kode fallback <strong>&lt;noscript&gt;</strong> milik Google Tag Manager.
                    </p>
                    <div class="relative">
                        <textarea id="header_body_scripts" name="header_body_scripts" rows="6" 
                            placeholder="<!-- Contoh Google Tag Manager (noscript) -->&#10;<noscript><iframe src=&quot;https://www.googletagmanager.com/ns.html?id=GTM-XXXXXXX&quot;&#10;height=&quot;0&quot; width=&quot;0&quot; style=&quot;display:none;visibility:hidden&quot;></iframe></noscript>"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-brand-500 transition-colors bg-slate-900 text-slate-100 font-mono text-xs leading-relaxed focus:outline-none placeholder-slate-500">{{ old('header_body_scripts', $settings['header_body_scripts'] ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Form Submit Button -->
                <div class="pt-4 flex items-center justify-between border-t border-gray-100">
                    <a href="{{ route('superuser.dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 transition">
                        &larr; Kembali ke Dashboard
                    </a>
                    <button type="submit" class="bg-brand-600 text-white px-6 py-3 rounded-xl font-medium shadow-lg shadow-brand-600/30 hover:bg-brand-700 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Simpan Pengaturan Header</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
