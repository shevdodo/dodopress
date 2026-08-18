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
    <x-frontend-header-scripts />
</head>

<body class="font-sans antialiased text-gray-900 bg-white min-h-screen flex flex-col overflow-x-hidden">
    <x-frontend-body-scripts />
    <x-frontend-navbar />

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


                <!-- Map -->
                <div class="space-y-8">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 sm:p-8 pb-4">
                            <h2 class="text-2xl font-bold text-gray-900">{{ $cc('map_title', 'Lokasi Kami') }}</h2>
                        </div>
                        <div class="w-full h-72 bg-gray-100">
                            {!! str_replace('<iframe ', '<iframe title="Location Map" class="w-full h-full" ', $cc('map_embed', '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126773.79389552635!2d106.71064250000001!3d-6.595038!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c5c5b5b5b5b5%3A0x0!2sJakarta!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>')) !!}
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">{{ $cc('social_title', 'Ikuti Kami') }}</h2>
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ $cc('social_facebook', '#') }}" target="_blank" rel="noopener noreferrer"
                               class="flex items-center gap-3 px-5 py-3 bg-gray-50 hover:bg-brand-50 border border-gray-100 hover:border-brand-200 rounded-xl text-gray-600 hover:text-brand-700 font-medium transition-all duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                <span>Facebook</span>
                            </a>
                            <a href="{{ $cc('social_instagram', '#') }}" target="_blank" rel="noopener noreferrer"
                               class="flex items-center gap-3 px-5 py-3 bg-gray-50 hover:bg-brand-50 border border-gray-100 hover:border-brand-200 rounded-xl text-gray-600 hover:text-brand-700 font-medium transition-all duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                <span>Instagram</span>
                            </a>

                            <a href="{{ $cc('social_twitter', '#') }}" target="_blank" rel="noopener noreferrer"
                               class="flex items-center gap-3 px-5 py-3 bg-gray-50 hover:bg-brand-50 border border-gray-100 hover:border-brand-200 rounded-xl text-gray-600 hover:text-brand-700 font-medium transition-all duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                                <span>Twitter</span>
                            </a>
                            <a href="{{ $cc('social_youtube', '#') }}" target="_blank" rel="noopener noreferrer"
                               class="flex items-center gap-3 px-5 py-3 bg-gray-50 hover:bg-brand-50 border border-gray-100 hover:border-brand-200 rounded-xl text-gray-600 hover:text-brand-700 font-medium transition-all duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                <span>YouTube</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-frontend-footer />
</body>
</html>
