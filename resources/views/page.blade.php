<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">

@php
    $siteTitle = \App\Models\Setting::where("key", "site_title")->value("value") ?: config("app.name", "Laravel");
    $tagline = \App\Models\Setting::where("key", "tagline")->value("value") ?: '';
    $favIcon = \App\Models\Setting::where("key", "fav_icon")->value("value");
    $siteLogo = \App\Models\Setting::where("key", "site_logo")->value("value");
    
    // Determine Meta Description to prevent raw JSON showing on share
    $metaDesc = $tagline ?: $siteTitle;
    if (!empty($page->content)) {
        json_decode($page->content);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Not JSON, use stripped text
            $metaDesc = \Illuminate\Support\Str::limit(strip_tags($page->content), 150);
        }
    }
    
    // Determine Meta Image
    $metaImage = $siteLogo ? asset("storage/" . $siteLogo) : ($favIcon ? asset("storage/" . $favIcon) : '');

    Artesaos\SEOTools\Facades\SEOMeta::setTitle($page->meta_title ?: $page->title . ' - ' . $siteTitle);
    Artesaos\SEOTools\Facades\SEOMeta::setDescription($page->meta_description ?: $metaDesc);
    if ($page->meta_keywords) {
        Artesaos\SEOTools\Facades\SEOMeta::addMeta('keywords', $page->meta_keywords);
    }
    
    Artesaos\SEOTools\Facades\OpenGraph::setTitle($page->meta_title ?: $page->title . ' - ' . $siteTitle);
    Artesaos\SEOTools\Facades\OpenGraph::setDescription($page->meta_description ?: $metaDesc);
    Artesaos\SEOTools\Facades\OpenGraph::setUrl(url()->current());
    Artesaos\SEOTools\Facades\OpenGraph::addProperty('type', 'website');
    if ($metaImage) {
        Artesaos\SEOTools\Facades\OpenGraph::addImage($metaImage);
    }

    Artesaos\SEOTools\Facades\JsonLd::setTitle($page->meta_title ?: $page->title . ' - ' . $siteTitle);
    Artesaos\SEOTools\Facades\JsonLd::setDescription($page->meta_description ?: $metaDesc);
    Artesaos\SEOTools\Facades\JsonLd::setType('WebPage');
    if ($metaImage) {
        Artesaos\SEOTools\Facades\JsonLd::addImage($metaImage);
    }
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! Artesaos\SEOTools\Facades\SEOTools::generate() !!}
    @if($page->meta_schema)
        {!! $page->meta_schema !!}
    @endif

    @if($favIcon)
        <link rel="icon" type="image/png" href="{{ asset("storage/" . $favIcon) }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <x-theme-config />
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50 min-h-screen flex flex-col">
    <x-frontend-navbar />

    <main class="flex-grow {{ $page->template === 'blank' ? '' : ($page->template === 'block' ? 'pb-0' : 'pt-24 pb-12') }}">
        @if($page->template === "block")
            @php
                $blocksData = [];
                if (!empty($page->content)) {
                    $decoded = json_decode($page->content, true);
                    if (json_last_error() === JSON_ERROR_NONE) $blocksData = $decoded["blocks"] ?? $decoded;
                }
            @endphp
            @if(is_array($blocksData) && count($blocksData) > 0)
                @foreach($blocksData as $block)
                    @php
                        $def = config("blocks.blocks." . $block["type"], []);
                        $component = $def["component"] ?? null;
                    @endphp
                    @if($component && view()->exists($component))
                        @include($component, ["data" => $block["data"] ?? []])
                    @endif
                @endforeach
            @else
                <div class="max-w-4xl mx-auto px-4 py-20 text-center text-gray-400">
                    <p>Halaman ini belum memiliki konten.</p>
                </div>
            @endif
        @elseif($page->template === "contact")
            @php
                $cp = [];
                if (!empty($page->content)) {
                    $decoded = json_decode($page->content, true);
                    if (json_last_error() === JSON_ERROR_NONE) $cp = $decoded;
                }
                $infoFields = [
                    ["t"=>"info_title_1","d"=>"info_desc_1","icon"=>"location"],
                    ["t"=>"info_title_2","d"=>"info_desc_2","icon"=>"phone"],
                    ["t"=>"info_title_3","d"=>"info_desc_3","icon"=>"email"],
                    ["t"=>"info_title_4","d"=>"info_desc_4","icon"=>"clock"],
                ];
                $icons = [
                    "location" => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                    "phone"    => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>',
                    "email"    => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                    "clock"    => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                ];
            @endphp

            {{-- ===== HERO ===== --}}
            <section class="relative overflow-hidden bg-gradient-to-br from-gray-950 via-gray-900 to-brand-950 text-white py-24 lg:py-36">
                {{-- Decorative orbs --}}
                <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-brand-600/20 rounded-full blur-[120px] pointer-events-none"></div>
                <div class="absolute -bottom-40 -right-20 w-[400px] h-[400px] bg-brand-800/20 rounded-full blur-[100px] pointer-events-none"></div>
                {{-- Dot grid --}}
                <div class="absolute inset-0 opacity-[0.04]" style="background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:32px 32px;"></div>

                <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    @if(!empty($cp["hero_badge"]))
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-brand-500/20 border border-brand-500/30 text-brand-300 text-sm font-medium mb-8 backdrop-blur-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand-400 animate-pulse"></span>
                        {{ $cp["hero_badge"] }}
                    </div>
                    @endif
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight mb-6 tracking-tight">
                        @if(!empty($cp["hero_title"]))
                            {!! nl2br(e($cp["hero_title"])) !!}
                        @else
                            Get In <span class="text-brand-400">Touch</span>
                        @endif
                    </h1>
                    @if(!empty($cp["hero_subtitle"]))
                    <p class="text-lg sm:text-xl text-gray-300/90 max-w-2xl mx-auto leading-relaxed">
                        {{ $cp["hero_subtitle"] }}
                    </p>
                    @endif
                    {{-- Scroll cue --}}
                    <div class="mt-12 flex justify-center">
                        <div class="w-px h-12 bg-gradient-to-b from-white/20 to-transparent"></div>
                    </div>
                </div>
            </section>

            {{-- ===== INFO CARDS ===== --}}
            @php $hasInfo = collect($infoFields)->contains(fn($f) => !empty($cp[$f["t"]])); @endphp
            @if($hasInfo)
            <section class="relative z-10 -mt-8 pb-0">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($infoFields as $i => $f)
                        @if(!empty($cp[$f["t"]]))
                        <div class="group bg-white rounded-2xl border border-gray-100 shadow-lg shadow-gray-200/60 p-6 flex flex-col items-start gap-4 hover:-translate-y-1 transition-all duration-300 hover:shadow-xl hover:border-brand-200">
                            <div class="w-12 h-12 rounded-xl bg-brand-50 group-hover:bg-brand-100 flex items-center justify-center text-brand-600 transition-colors duration-300">
                                {!! $icons[$f["icon"]] !!}
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-brand-600 uppercase tracking-widest mb-1">{{ $cp[$f["t"]] }}</p>
                                <p class="text-gray-700 text-sm leading-relaxed font-medium">{{ $cp[$f["d"]] ?? "" }}</p>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </section>
            @endif

            {{-- ===== FORM + MAP ===== --}}
            <section class="py-20 bg-gradient-to-b from-gray-50 to-white">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-10 lg:gap-16 items-start">

                        {{-- Contact Form --}}
                        <div class="lg:col-span-3">
                            <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/50 p-8 sm:p-10">
                                @if(!empty($cp["form_title"]))
                                <div class="mb-8">
                                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">{{ $cp["form_title"] }}</h2>
                                    @if(!empty($cp["form_subtitle"]))
                                    <p class="text-gray-500 text-sm">{{ $cp["form_subtitle"] }}</p>
                                    @endif
                                    <div class="mt-4 h-1 w-12 rounded-full bg-brand-500"></div>
                                </div>
                                @endif

                                @if(session("success"))
                                <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium">
                                    <svg class="w-5 h-5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ session("success") }}
                                </div>
                                @endif
                                @if($errors->any())
                                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm">
                                    <ul class="space-y-1 list-disc list-inside">
                                        @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                                    </ul>
                                </div>
                                @endif

                                <form method="POST" action="{{ route("contact.send") }}" class="space-y-5">
                                    @csrf
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                        <div class="relative">
                                            <input type="text" id="cf_name" name="name" value="{{ old("name") }}" placeholder=" " required
                                                class="peer w-full border border-gray-200 rounded-xl px-4 pt-5 pb-2.5 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 transition outline-none placeholder-transparent">
                                            <label for="cf_name" class="absolute left-4 top-1 text-[10px] font-semibold text-brand-500 uppercase tracking-wider peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-placeholder-shown:font-normal peer-placeholder-shown:text-gray-400 peer-placeholder-shown:tracking-normal peer-placeholder-shown:uppercase-none transition-all pointer-events-none">Nama Lengkap</label>
                                        </div>
                                        <div class="relative">
                                            <input type="email" id="cf_email" name="email" value="{{ old("email") }}" placeholder=" " required
                                                class="peer w-full border border-gray-200 rounded-xl px-4 pt-5 pb-2.5 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 transition outline-none placeholder-transparent">
                                            <label for="cf_email" class="absolute left-4 top-1 text-[10px] font-semibold text-brand-500 uppercase tracking-wider peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-placeholder-shown:font-normal peer-placeholder-shown:text-gray-400 peer-placeholder-shown:tracking-normal transition-all pointer-events-none">Email</label>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <input type="text" id="cf_subject" name="subject" value="{{ old("subject") }}" placeholder=" "
                                            class="peer w-full border border-gray-200 rounded-xl px-4 pt-5 pb-2.5 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 transition outline-none placeholder-transparent">
                                        <label for="cf_subject" class="absolute left-4 top-1 text-[10px] font-semibold text-brand-500 uppercase tracking-wider peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-placeholder-shown:font-normal peer-placeholder-shown:text-gray-400 peer-placeholder-shown:tracking-normal transition-all pointer-events-none">Subjek</label>
                                    </div>
                                    <div class="relative">
                                        <textarea id="cf_message" name="message" rows="5" placeholder=" " required
                                            class="peer w-full border border-gray-200 rounded-xl px-4 pt-5 pb-2.5 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 transition outline-none resize-none placeholder-transparent">{{ old("message") }}</textarea>
                                        <label for="cf_message" class="absolute left-4 top-1 text-[10px] font-semibold text-brand-500 uppercase tracking-wider peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-placeholder-shown:font-normal peer-placeholder-shown:text-gray-400 peer-placeholder-shown:tracking-normal transition-all pointer-events-none">Pesan</label>
                                    </div>
                                    <button type="submit"
                                        class="group w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl shadow-lg shadow-brand-600/30 hover:shadow-brand-600/40 transition-all duration-200 text-sm">
                                        <span>{{ $cp["form_button"] ?? "Kirim Pesan" }}</span>
                                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                    <p class="text-center text-xs text-gray-400">Kami akan merespons dalam 1×24 jam kerja.</p>
                                </form>
                            </div>
                        </div>

                        {{-- Map + Social --}}
                        <div class="lg:col-span-2 space-y-6">
                            {{-- Map --}}
                            <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/50 overflow-hidden">
                                @if(!empty($cp["map_embed"]))
                                    <div class="w-full h-56 sm:h-64 [&_iframe]:w-full [&_iframe]:h-full">
                                        {!! $cp["map_embed"] !!}
                                    </div>
                                @else
                                    <div class="h-56 flex flex-col items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 text-gray-400 gap-2">
                                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        <span class="text-sm">Peta belum dikonfigurasi</span>
                                    </div>
                                @endif
                                @if(!empty($cp["map_title"]))
                                <div class="px-5 py-4 border-t border-gray-50">
                                    <p class="font-semibold text-gray-800 text-sm">{{ $cp["map_title"] }}</p>
                                </div>
                                @endif
                            </div>

                            {{-- Social Media --}}
                            @php
                                $socials = [
                                    ["key"=>"social_facebook","label"=>"Facebook","color"=>"#1877F2","hover"=>"#1565c0","icon"=>'<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>'],
                                    ["key"=>"social_instagram","label"=>"Instagram","color"=>"#E1306C","hover"=>"#c13584","icon"=>'<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>'],
                                    ["key"=>"social_twitter","label"=>"X / Twitter","color"=>"#000","hover"=>"#333","icon"=>'<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>'],
                                    ["key"=>"social_youtube","label"=>"YouTube","color"=>"#FF0000","hover"=>"#cc0000","icon"=>'<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>'],
                                ];
                                $activeSocials = array_filter($socials, fn($s) => !empty($cp[$s["key"]]) && $cp[$s["key"]] !== "#");
                            @endphp
                            @if(count($activeSocials) > 0)
                            <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/50 p-6">
                                @if(!empty($cp["social_title"]))
                                <h3 class="font-bold text-gray-900 mb-1">{{ $cp["social_title"] }}</h3>
                                <p class="text-xs text-gray-400 mb-4">Ikuti kami di platform favorit Anda</p>
                                @endif
                                <div class="flex flex-wrap gap-3">
                                    @foreach($activeSocials as $s)
                                    <a href="{{ $cp[$s["key"]] }}" target="_blank" rel="noopener"
                                        class="group flex items-center gap-2.5 px-4 py-2.5 rounded-xl border border-gray-100 hover:border-transparent text-gray-700 hover:text-white text-sm font-medium transition-all duration-200 hover:shadow-md"
                                        style="--hover-bg:{{ $s["color"] }}"
                                        onmouseover="this.style.background='{{ $s["color"] }}'" onmouseout="this.style.background=''">
                                        {!! $s["icon"] !!}
                                        <span>{{ $s["label"] }}</span>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                    </div>
                </div>
            </section>

            {{-- ===== BOTTOM CTA STRIP ===== --}}
            <section class="bg-gradient-to-r from-brand-600 to-brand-700 py-12">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
                    <p class="text-lg font-semibold mb-1">Punya pertanyaan mendesak?</p>
                    <p class="text-brand-100 text-sm">Tim kami siap membantu Anda — jangan ragu untuk menghubungi kami.</p>
                </div>
            </section>

        @elseif($page->template === "blank")
            {!! $page->content !!}
        @else
        <div class="{{ $page->template === "full-width" ? "w-full" : "max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" }}">
            <div class="{{ $page->template === "full-width" ? "" : "bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden" }}">
                <div class="{{ $page->template === "full-width" ? "px-4 sm:px-8 py-10 sm:py-16" : "px-8 py-10 sm:px-12 sm:py-16" }}">
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 tracking-tight mb-8 {{ $page->template === "full-width" ? "text-center" : "" }}">{{ $page->title }}</h1>
                    <div class="prose prose-lg prose-indigo {{ $page->template === "full-width" ? "max-w-7xl mx-auto" : "max-w-none" }} text-gray-600 leading-relaxed">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </main>
    <x-frontend-footer />
</body>
</html>

