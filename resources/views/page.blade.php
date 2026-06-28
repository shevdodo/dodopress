<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">

@php
    $siteTitle = \App\Models\Setting::where("key", "site_title")->value("value") ?: config("app.name", "Laravel");
    $favIcon = \App\Models\Setting::where("key", "fav_icon")->value("value");
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($page->content ?? $siteTitle), 150) }}">
    <title>{{ $page->title }} - {{ $siteTitle }}</title>
    @if($favIcon)
        <link rel="icon" type="image/png" href="{{ asset("storage/" . $favIcon) }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <x-theme-config />
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50 min-h-screen flex flex-col">
    <x-frontend-navbar />

    <main class="flex-grow {{ $page->template === "blank" ? "" : "pt-24 pb-12" }}">
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
                $infoFields = [["t"=>"info_title_1","d"=>"info_desc_1"],["t"=>"info_title_2","d"=>"info_desc_2"],["t"=>"info_title_3","d"=>"info_desc_3"],["t"=>"info_title_4","d"=>"info_desc_4"]];
            @endphp
            @if(!empty($cp["hero_title"]))
            <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white py-20 lg:py-28">
                <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    @if(!empty($cp["hero_badge"]))<span class="inline-block px-4 py-1.5 rounded-full bg-white/10 text-sm font-medium text-white/90 mb-6 border border-white/10">{{ $cp["hero_badge"] }}</span>@endif
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight mb-6">{{ $cp["hero_title"] }}</h1>
                    @if(!empty($cp["hero_subtitle"]))<p class="text-lg sm:text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed">{{ $cp["hero_subtitle"] }}</p>@endif
                </div>
            </section>
            @endif
            @if(!empty($cp["info_title_1"]))
            <section class="py-16 bg-white">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($infoFields as $f) @if(!empty($cp[$f["t"]]))
                    <div class="p-6 rounded-2xl border border-gray-100 shadow-sm text-center hover:shadow-md transition">
                        <h3 class="font-bold text-gray-900 mb-2">{{ $cp[$f["t"]] }}</h3>
                        <p class="text-gray-600 text-sm">{{ $cp[$f["d"]] ?? "" }}</p>
                    </div>
                    @endif @endforeach
                </div>
            </section>
            @endif
            <section class="py-16 bg-gray-50">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <div>
                        @if(!empty($cp["form_title"]))
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $cp["form_title"] }}</h2>
                        <p class="text-gray-600 mb-6">{{ $cp["form_subtitle"] ?? "" }}</p>
                        @endif
                        <form method="POST" action="{{ route("contact.send") }}" class="space-y-4">
                            @csrf
                            <input type="text" name="name" placeholder="Nama Lengkap" class="w-full border-gray-300 rounded-xl px-4 py-3 focus:border-brand-500 focus:ring-brand-500/20 focus:ring">
                            <input type="email" name="email" placeholder="Email" class="w-full border-gray-300 rounded-xl px-4 py-3 focus:border-brand-500 focus:ring-brand-500/20 focus:ring">
                            <input type="text" name="subject" placeholder="Subjek" class="w-full border-gray-300 rounded-xl px-4 py-3 focus:border-brand-500 focus:ring-brand-500/20 focus:ring">
                            <textarea name="message" rows="5" placeholder="Pesan" class="w-full border-gray-300 rounded-xl px-4 py-3 focus:border-brand-500 focus:ring-brand-500/20 focus:ring"></textarea>
                            <button type="submit" class="w-full px-6 py-3 bg-brand-600 text-white font-semibold rounded-xl hover:bg-brand-700 transition shadow-lg shadow-brand-600/30">{{ $cp["form_button"] ?? "Kirim Pesan" }}</button>
                        </form>
                    </div>
                    <div>
                        @if(!empty($cp["map_title"]))<h3 class="text-xl font-bold text-gray-900 mb-4">{{ $cp["map_title"] }}</h3>@endif
                        @if(!empty($cp["map_embed"]))<div class="rounded-2xl overflow-hidden shadow-sm">{!! $cp["map_embed"] !!}</div>@else<div class="rounded-2xl bg-gray-200 h-64 flex items-center justify-center text-gray-400">Peta</div>@endif
                        @if(!empty($cp["social_title"]))
                        <h3 class="text-xl font-bold text-gray-900 mt-8 mb-4">{{ $cp["social_title"] }}</h3>
                        <div class="flex gap-4">

                            @if(!empty($cp["social_facebook"]) && $cp["social_facebook"] !== "#")<a href="{{ $cp["social_facebook"] }}" target="_blank" class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>@endif
                            @if(!empty($cp["social_instagram"]) && $cp["social_instagram"] !== "#")<a href="{{ $cp["social_instagram"] }}" target="_blank" class="w-12 h-12 rounded-xl bg-pink-600 text-white flex items-center justify-center hover:bg-pink-700 transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>@endif
                            @if(!empty($cp["social_twitter"]) && $cp["social_twitter"] !== "#")<a href="{{ $cp["social_twitter"] }}" target="_blank" class="w-12 h-12 rounded-xl bg-sky-500 text-white flex items-center justify-center hover:bg-sky-600 transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>@endif
                            @if(!empty($cp["social_youtube"]) && $cp["social_youtube"] !== "#")<a href="{{ $cp["social_youtube"] }}" target="_blank" class="w-12 h-12 rounded-xl bg-red-600 text-white flex items-center justify-center hover:bg-red-700 transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg></a>@endif
                        </div>
                        @endif
                    </div>
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

