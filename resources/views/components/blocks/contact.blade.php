@props(['data' => []])
@php
    $badge        = $data['badge']        ?? 'Hubungi Kami';
    $title        = $data['title']        ?? 'Get In Touch';
    $subtitle     = $data['subtitle']     ?? 'Kami siap membantu Anda. Kirimkan pesan dan tim kami akan merespons dalam 1×24 jam kerja.';
    $info1_title  = $data['info1_title']  ?? 'Alamat';
    $info1_desc   = $data['info1_desc']   ?? '';
    $info2_title  = $data['info2_title']  ?? 'Telepon';
    $info2_desc   = $data['info2_desc']   ?? '';
    $info3_title  = $data['info3_title']  ?? 'Email';
    $info3_desc   = $data['info3_desc']   ?? '';
    $info4_title  = $data['info4_title']  ?? 'Jam Operasional';
    $info4_desc   = $data['info4_desc']   ?? '';
    $form_title   = $data['form_title']   ?? 'Kirim Pesan';
    $form_subtitle= $data['form_subtitle']?? 'Isi formulir di bawah dan kami akan segera menghubungi Anda.';
    $btn_text     = $data['btn_text']     ?? 'Kirim Pesan';
    $map_embed    = $data['map_embed']    ?? '';
    $map_title    = $data['map_title']    ?? 'Lokasi Kami';
    $social_fb    = $data['social_fb']    ?? '';
    $social_ig    = $data['social_ig']    ?? '';
    $social_tw    = $data['social_tw']    ?? '';
    $social_yt    = $data['social_yt']    ?? '';
    $social_wa    = $data['social_wa']    ?? '';

    $infoCards = [
        ['icon'=>'location','title'=>$info1_title,'desc'=>$info1_desc],
        ['icon'=>'phone',   'title'=>$info2_title,'desc'=>$info2_desc],
        ['icon'=>'email',   'title'=>$info3_title,'desc'=>$info3_desc],
        ['icon'=>'clock',   'title'=>$info4_title,'desc'=>$info4_desc],
    ];
    $icons = [
        'location' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
        'phone'    => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>',
        'email'    => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
        'clock'    => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    ];
    $socials = array_filter([
        ['url'=>$social_wa, 'label'=>'WhatsApp',  'bg'=>'#25D366', 'icon'=>'<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>'],
        ['url'=>$social_fb, 'label'=>'Facebook',  'bg'=>'#1877F2', 'icon'=>'<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>'],
        ['url'=>$social_ig, 'label'=>'Instagram', 'bg'=>'#E1306C', 'icon'=>'<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>'],
        ['url'=>$social_tw, 'label'=>'X / Twitter','bg'=>'#111',   'icon'=>'<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>'],
        ['url'=>$social_yt, 'label'=>'YouTube',   'bg'=>'#FF0000', 'icon'=>'<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>'],
    ], fn($s) => !empty($s['url']));
@endphp

{{-- ===== HERO ===== --}}
<section class="relative overflow-hidden bg-gradient-to-br from-gray-950 via-gray-900 to-brand-900 text-white pt-40 pb-24 lg:pt-48 lg:pb-36">
    <div class="absolute -top-40 -left-40 w-[520px] h-[520px] bg-brand-600/20 rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-20 w-[400px] h-[400px] bg-brand-700/20 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute inset-0 opacity-[0.04]" style="background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:32px 32px;"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        @if($badge)
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-brand-500/20 border border-brand-500/30 text-brand-300 text-sm font-medium mb-8 backdrop-blur-sm">
            <span class="w-1.5 h-1.5 rounded-full bg-brand-400 animate-pulse"></span>
            {{ $badge }}
        </div>
        @endif
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight mb-6 tracking-tight">
            {!! nl2br(e($title)) !!}
        </h1>
        @if($subtitle)
        <p class="text-lg sm:text-xl text-gray-300/90 max-w-2xl mx-auto leading-relaxed">{{ $subtitle }}</p>
        @endif
        <div class="mt-12 flex justify-center">
            <div class="w-px h-12 bg-gradient-to-b from-white/25 to-transparent"></div>
        </div>
    </div>
</section>

{{-- ===== INFO CARDS ===== --}}
@php $hasInfo = collect($infoCards)->contains(fn($c) => !empty($c['desc'])); @endphp
@if($hasInfo)
<section class="relative z-10 -mt-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-center gap-4">
            @foreach($infoCards as $card)
            @if(!empty($card['desc']))
            <div class="w-full sm:w-[calc(50%-0.5rem)] lg:w-[calc(25%-0.75rem)] group bg-white rounded-2xl border border-gray-100 shadow-lg shadow-gray-900/[0.07] p-5 flex items-start gap-4 hover:-translate-y-1 hover:shadow-xl hover:border-brand-200 transition-all duration-300">
                <div class="w-10 h-10 shrink-0 rounded-xl bg-brand-50 group-hover:bg-brand-100 flex items-center justify-center text-brand-600 transition-colors duration-300">
                    {!! $icons[$card['icon']] !!}
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] font-bold text-brand-500 uppercase tracking-widest mb-0.5">{{ $card['title'] }}</p>
                    <p class="text-gray-700 text-sm leading-relaxed">{!! nl2br(e($card['desc'])) !!}</p>
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
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-10 lg:gap-14 items-start">

            {{-- Contact Form --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-900/[0.07] p-8 sm:p-10">
                    <div class="mb-8">
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">{{ $form_title }}</h2>
                        @if($form_subtitle)
                        <p class="text-gray-500 text-sm">{{ $form_subtitle }}</p>
                        @endif
                        <div class="mt-4 h-1 w-10 rounded-full bg-brand-500"></div>
                    </div>

                    @if(session('contact_success'))
                    <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium">
                        <svg class="w-5 h-5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('contact_success') }}
                    </div>
                    @endif
                    @if(session('success'))
                    <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium">
                        <svg class="w-5 h-5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('success') }}
                    </div>
                    @endif
                    @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm">
                        <ul class="space-y-1 list-disc list-inside">
                            @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('contact.send') }}" class="space-y-5">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="relative">
                                <input type="text" id="blk_cf_name" name="name" value="{{ old('name') }}" placeholder=" " required
                                    class="peer w-full border border-gray-200 rounded-xl px-4 pt-5 pb-2.5 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 transition outline-none placeholder-transparent">
                                <label for="blk_cf_name" class="absolute left-4 top-1 text-[10px] font-semibold text-brand-500 uppercase tracking-wider transition-all pointer-events-none
                                    peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-placeholder-shown:font-normal peer-placeholder-shown:text-gray-400 peer-placeholder-shown:tracking-normal peer-placeholder-shown:uppercase-none">
                                    Nama Lengkap
                                </label>
                            </div>
                            <div class="relative">
                                <input type="email" id="blk_cf_email" name="email" value="{{ old('email') }}" placeholder=" " required
                                    class="peer w-full border border-gray-200 rounded-xl px-4 pt-5 pb-2.5 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 transition outline-none placeholder-transparent">
                                <label for="blk_cf_email" class="absolute left-4 top-1 text-[10px] font-semibold text-brand-500 uppercase tracking-wider transition-all pointer-events-none
                                    peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-placeholder-shown:font-normal peer-placeholder-shown:text-gray-400 peer-placeholder-shown:tracking-normal">
                                    Email
                                </label>
                            </div>
                        </div>
                        <div class="relative">
                            <input type="tel" id="blk_cf_phone" name="phone" value="{{ old('phone') }}" placeholder=" "
                                class="peer w-full border border-gray-200 rounded-xl px-4 pt-5 pb-2.5 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 transition outline-none placeholder-transparent">
                            <label for="blk_cf_phone" class="absolute left-4 top-1 text-[10px] font-semibold text-brand-500 uppercase tracking-wider transition-all pointer-events-none
                                peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-placeholder-shown:font-normal peer-placeholder-shown:text-gray-400 peer-placeholder-shown:tracking-normal">
                                No. Telepon
                            </label>
                        </div>
                        <div class="relative">
                            <input type="text" id="blk_cf_subject" name="subject" value="{{ old('subject') }}" placeholder=" "
                                class="peer w-full border border-gray-200 rounded-xl px-4 pt-5 pb-2.5 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 transition outline-none placeholder-transparent">
                            <label for="blk_cf_subject" class="absolute left-4 top-1 text-[10px] font-semibold text-brand-500 uppercase tracking-wider transition-all pointer-events-none
                                peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-placeholder-shown:font-normal peer-placeholder-shown:text-gray-400 peer-placeholder-shown:tracking-normal">
                                Subjek
                            </label>
                        </div>
                        <div class="relative">
                            <textarea id="blk_cf_message" name="message" rows="5" placeholder=" " required
                                class="peer w-full border border-gray-200 rounded-xl px-4 pt-5 pb-2.5 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 transition outline-none resize-none placeholder-transparent">{{ old('message') }}</textarea>
                            <label for="blk_cf_message" class="absolute left-4 top-1 text-[10px] font-semibold text-brand-500 uppercase tracking-wider transition-all pointer-events-none
                                peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-placeholder-shown:font-normal peer-placeholder-shown:text-gray-400 peer-placeholder-shown:tracking-normal">
                                Pesan
                            </label>
                        </div>
                        <button type="submit"
                            class="group w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl shadow-lg shadow-brand-600/25 hover:shadow-brand-600/35 transition-all duration-200 text-sm">
                            <span>{{ $btn_text }}</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                        <p class="text-center text-xs text-gray-400">🔒 Data Anda aman. Kami tidak menyebarkan informasi pribadi Anda.</p>
                    </form>
                </div>
            </div>

            {{-- Map + Social --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Map Card --}}
                @if($map_embed)
                <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-900/[0.07] overflow-hidden">
                    <div class="w-full h-60 [&_iframe]:w-full [&_iframe]:h-full [&_iframe]:border-0">
                        {!! $map_embed !!}
                    </div>
                    @if($map_title)
                    <div class="px-5 py-3.5 border-t border-gray-50 flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        <p class="font-semibold text-gray-800 text-sm">{{ $map_title }}</p>
                    </div>
                    @endif
                </div>
                @endif

                {{-- Social Media Card --}}
                @if(count($socials) > 0)
                <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-900/[0.07] p-6">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Temukan Kami Di</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($socials as $s)
                        <a href="{{ $s['url'] }}" target="_blank" rel="noopener"
                            class="group flex w-full justify-center items-center gap-2 px-3.5 py-3 rounded-xl border border-gray-100 text-gray-600 hover:text-white text-sm font-medium transition-all duration-200 hover:shadow-md hover:border-transparent"
                            onmouseover="this.style.background='{{ $s['bg'] }}';this.style.color='#fff'"
                            onmouseout="this.style.background='';this.style.color=''">
                            {!! $s['icon'] !!}
                            <span>{{ $s['label'] }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Quick Response Badge --}}
                <div class="bg-gradient-to-r from-brand-600 to-brand-700 rounded-2xl p-5 text-white">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-sm mb-0.5">Respons Cepat</p>
                            <p class="text-brand-100 text-xs leading-relaxed">Tim kami aktif dan siap membalas pesan Anda dalam 1×24 jam di hari kerja.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
