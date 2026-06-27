<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@php
    $siteTitle = \App\Models\Setting::where('key', 'site_title')->value('value') ?: config('app.name', 'Laravel');
    $favIcon = \App\Models\Setting::where('key', 'fav_icon')->value('value');
    $cpContent = [];
    if (!empty($page->content)) {
        $decoded = json_decode($page->content, true);
        if (json_last_error() === JSON_ERROR_NONE) $cpContent = $decoded;
    }
    $cc = function($key, $default = '') use ($cpContent) { return $cpContent[$key] ?? $default; };
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $cc('hero_subtitle', 'Hubungi kami untuk informasi lebih lanjut.') }}">
    <title>{{ $cc('hero_title', 'Contact Us') }} - {{ $siteTitle }}</title>
    @if($favIcon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $favIcon) }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <x-theme-config />
    <style>
        @keyframes fadeInUp { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }
        .animate-fade-in-up { animation:fadeInUp 0.8s ease-out forwards; }
        .stagger-1 { animation-delay:0.1s; } .stagger-2 { animation-delay:0.2s; }
        .stagger-3 { animation-delay:0.3s; } .stagger-4 { animation-delay:0.4s; }
    </style>
</head>

    <!-- ===== HERO ===== -->
    <section class="relative pt-32 pb-20 md:pt-40 md:pb-28 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-900 via-brand-800 to-brand-950"></div>
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-10 left-1/4 w-96 h-96 bg-brand-400 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/3 w-80 h-80 bg-brand-300 rounded-full blur-3xl"></div>
        </div>
        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block px-4 py-2 bg-white/10 backdrop-blur-sm text-white rounded-full text-sm font-semibold mb-6 animate-fade-in-up">{{ $cc('hero_badge', 'Hubungi Kami') }}</span>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-white leading-tight animate-fade-in-up stagger-1">{{ $cc('hero_title', 'Get In Touch') }}</h1>
            <p class="mt-6 text-lg sm:text-xl text-brand-200 max-w-2xl mx-auto animate-fade-in-up stagger-2">{{ $cc('hero_subtitle', 'Kami siap membantu Anda. Silakan hubungi kami melalui form di bawah atau informasi kontak yang tersedia.') }}</p>
        </div>
    </section>

    <!-- ===== CONTACT INFO CARDS ===== -->
    <section class="relative -mt-16 pb-16 z-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="animate-fade-in-up bg-white rounded-2xl shadow-lg border border-gray-100 p-6 text-center hover:shadow-xl transition-shadow duration-300 group">
                    <div class="w-14 h-14 mx-auto bg-brand-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-brand-600 transition-all duration-300">
                        <svg class="w-7 h-7 text-brand-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1">{{ $cc('info_title_1', 'Alamat') }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $cc('info_desc_1', 'Jl. Contoh No. 123, Kota Contoh, Provinsi 12345') }}</p>
                </div>
                <div class="animate-fade-in-up stagger-1 bg-white rounded-2xl shadow-lg border border-gray-100 p-6 text-center hover:shadow-xl transition-shadow duration-300 group">
                    <div class="w-14 h-14 mx-auto bg-brand-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-brand-600 transition-all duration-300">
                        <svg class="w-7 h-7 text-brand-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1">{{ $cc('info_title_2', 'Telepon') }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $cc('info_desc_2', '+62 812-3456-7890') }}</p>
                </div>
                <div class="animate-fade-in-up stagger-2 bg-white rounded-2xl shadow-lg border border-gray-100 p-6 text-center hover:shadow-xl transition-shadow duration-300 group">
                    <div class="w-14 h-14 mx-auto bg-brand-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-brand-600 transition-all duration-300">
                        <svg class="w-7 h-7 text-brand-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1">{{ $cc('info_title_3', 'Email') }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $cc('info_desc_3', 'info@example.com') }}</p>
                </div>
                <div class="animate-fade-in-up stagger-3 bg-white rounded-2xl shadow-lg border border-gray-100 p-6 text-center hover:shadow-xl transition-shadow duration-300 group">
                    <div class="w-14 h-14 mx-auto bg-brand-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-brand-600 transition-all duration-300">
                        <svg class="w-7 h-7 text-brand-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1">{{ $cc('info_title_4', 'Jam Operasional') }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $cc('info_desc_4', 'Sen - Sab: 08:00 - 17:00') }}</p>
                </div>
            </div>
        </div>
    </section>


    <!-- ===== FORM & MAP ===== -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                <!-- Contact Form -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 sm:p-10">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">{{ $cc('form_title', 'Kirim Pesan') }}</h2>
                    <p class="text-gray-500 mb-8">{{ $cc('form_subtitle', 'Isi form di bawah dan tim kami akan menghubungi Anda segera.') }}</p>
                    <form method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-400">*</span></label>
                                <input type="text" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition bg-gray-50/50" placeholder="Nama Anda">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email <span class="text-red-400">*</span></label>
                                <input type="email" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition bg-gray-50/50" placeholder="email@anda.com">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Subjek</label>
                            <input type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition bg-gray-50/50" placeholder="Subjek pesan">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pesan <span class="text-red-400">*</span></label>
                            <textarea rows="5" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition bg-gray-50/50" placeholder="Tulis pesan Anda di sini..."></textarea>
                        </div>
                        <button type="submit" class="w-full px-8 py-4 bg-gradient-to-r from-brand-600 to-brand-700 text-white font-bold rounded-xl hover:from-brand-700 hover:to-brand-800 transition-all duration-300 shadow-lg shadow-brand-600/30 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                            {{ $cc('form_button', 'Kirim Pesan') }}
                        </button>
                    </form>
                </div>


<body class="font-sans antialiased text-gray-900 bg-white min-h-screen flex flex-col overflow-x-hidden">
    <x-frontend-navbar />
