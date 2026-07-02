<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UX Builder - {{ $page->title }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $favIcon = \App\Models\Setting::where('key', 'fav_icon')->value('value');
    @endphp
    @if($favIcon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $favIcon) }}">
    @endif

    <!-- GrapesJS -->
    <link href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" rel="stylesheet">
    <script src="https://unpkg.com/grapesjs"></script>
    <script src="https://unpkg.com/grapesjs-preset-webpage"></script>
    <script src="https://unpkg.com/grapesjs-blocks-basic"></script>
    <script src="https://unpkg.com/grapesjs-plugin-slider"></script>
    
    <!-- Tailwind CSS (for the top bar) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body, html { margin: 0; padding: 0; height: 100%; overflow: hidden; }
        #gjs { height: calc(100vh - 50px); overflow: hidden; position: relative; }
        /* Layout Customization (Left Sidebar) */
        .gjs-cv-canvas {
            width: calc(100% - 280px) !important;
            left: 280px !important;
            right: auto !important;
        }
        .gjs-pn-views-container {
            left: 0 !important;
            right: auto !important;
            width: 280px !important;
            border-right: 1px solid #333;
        }
        .gjs-pn-views {
            left: 0 !important;
            right: auto !important;
            width: 280px !important;
            border-right: 1px solid #333;
        }
        
        /* Fix Top Panels Position (Undo/Redo & Devices) */
        .gjs-pn-commands, .gjs-pn-options, .gjs-pn-devices-c {
            left: 280px !important; /* Start after the left sidebar */
            box-shadow: none !important;
            background: transparent !important;
            width: auto !important;
        }
        .gjs-pn-commands {
            left: 290px !important; /* little padding */
        }
        .gjs-pn-options {
            right: 0 !important;
            left: auto !important;
        }
        .gjs-pn-devices-c {
            left: calc(50% + 140px) !important;
            transform: translateX(-50%);
        }

        .builder-topbar {
            height: 50px;
            background: #1e1e1e;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1rem;
            color: white;
            box-sizing: border-box;
            border-bottom: 1px solid #333;
            position: relative;
            z-index: 1000;
        }
    </style>
</head>
<body>
    
    <div class="builder-topbar border-b border-gray-700">
        <div class="flex items-center gap-4">
            <a href="{{ route('superuser.pages.edit', $page->id) }}" class="text-gray-300 hover:text-white transition flex items-center gap-1 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Back to Edit
            </a>
            <h1 class="font-semibold text-sm border-l border-gray-600 pl-4">UX Builder: <span class="text-brand-400">{{ $page->title }}</span></h1>
        </div>
        <div>
            <button id="save-btn" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1.5 rounded text-sm font-semibold transition shadow flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                Save Design
            </button>
        </div>
    </div>

    <div id="gjs">
        @if($page->content)
            {!! $page->content !!}
        @else
            <h1>Build your page here</h1>
        @endif
    </div>

    <!-- Notification Toast -->
    <div id="toast" class="fixed bottom-4 right-4 bg-gray-800 text-white px-6 py-3 rounded-lg shadow-xl transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none z-50">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            <span id="toast-msg">Saved!</span>
        </div>
    </div>

    <script>
        // Check if we have saved builder data
        const savedData = {!! $page->builder_data ? json_encode($page->builder_data) : 'null' !!};
        
        const editor = grapesjs.init({
            container: '#gjs',
            fromElement: true,
            height: '100%',
            width: 'auto',
            storageManager: false, // We will handle storage manually
            plugins: ['gjs-preset-webpage', 'gjs-blocks-basic', 'grapesjs-plugin-slider'],
            pluginsOpts: {
                'gjs-preset-webpage': {
                    // options
                },
                'grapesjs-plugin-slider': {
                    sliderBlock: {
                        category: 'Basic'
                    }
                }
            },
            canvas: {
                scripts: [
                    'https://cdn.tailwindcss.com',
                    'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js'
                ],
                styles: [
                    'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css'
                ]
            }
        });

        // Add Custom Layout Blocks
        const bm = editor.BlockManager;

        const col1Svg = '<svg width="100px" height="68px" viewBox="0 0 100 68" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <title>row-1-col</title> <defs> <rect id="path-1" x="0" y="0" width="100" height="68"></rect> <linearGradient x1="0%" y1="0%" x2="106.265625%" y2="109.895844%" id="linearGradient-3"> <stop stop-color="#C8EAF4" stop-opacity="0.208021966" offset="0%"></stop> <stop stop-color="#3DD0FF" offset="100%"></stop> </linearGradient> <rect id="path-4" x="12" y="12" width="76" height="43"></rect> <mask id="mask-5" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="76" height="43" fill="white"> <use xlink:href="#path-4"></use> </mask> </defs> <g id="Row-Presets" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="row-1-col"> <mask id="mask-2" fill="white"> <use xlink:href="#path-1"></use> </mask> <use id="BG" fill="#FFFFFF" xlink:href="#path-1"></use> <g id="Rectangle-486-Copy" mask="url(#mask-2)" stroke="#00A0D2" stroke-width="2" fill="url(#linearGradient-3)" fill-opacity="0.15"> <use mask="url(#mask-5)" xlink:href="#path-4"></use> </g> </g> </g> </svg>';
        const col2Svg = '<svg width="100px" height="68px" viewBox="0 0 100 68" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <title>row-2-col</title> <defs> <rect id="path-1" x="0" y="0" width="100" height="68"></rect> <linearGradient x1="0%" y1="0%" x2="106.265625%" y2="109.895844%" id="linearGradient-3"> <stop stop-color="#C8EAF4" stop-opacity="0.208021966" offset="0%"></stop> <stop stop-color="#3DD0FF" offset="100%"></stop> </linearGradient> <rect id="path-4" x="11" y="12" width="36.016129" height="43"></rect> <mask id="mask-5" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="36.016129" height="43" fill="white"> <use xlink:href="#path-4"></use> </mask> <rect id="path-6" x="51.983871" y="12" width="36.016129" height="43"></rect> <mask id="mask-7" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="36.016129" height="43" fill="white"> <use xlink:href="#path-6"></use> </mask> </defs> <g id="Row-Presets" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="row-2-col"> <mask id="mask-2" fill="white"> <use xlink:href="#path-1"></use> </mask> <use id="BG" fill="#FFFFFF" xlink:href="#path-1"></use> <g id="Rectangle-486-Copy" mask="url(#mask-2)" stroke="#00A0D2" stroke-width="2" fill="url(#linearGradient-3)" fill-opacity="0.15"> <use mask="url(#mask-5)" xlink:href="#path-4"></use> </g> <g id="Rectangle-486-Copy" mask="url(#mask-2)" stroke="#00A0D2" stroke-width="2" fill="url(#linearGradient-3)" fill-opacity="0.15"> <use mask="url(#mask-7)" xlink:href="#path-6"></use> </g> </g> </g> </svg>';
        const col3Svg = '<svg width="100px" height="68px" viewBox="0 0 100 68" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <title>row-3-col</title> <defs> <rect id="path-1" x="0" y="0" width="100" height="68"></rect> <linearGradient x1="0%" y1="0%" x2="106.265625%" y2="109.895844%" id="linearGradient-3"> <stop stop-color="#C8EAF4" stop-opacity="0.208021966" offset="0%"></stop> <stop stop-color="#3DD0FF" offset="100%"></stop> </linearGradient> <rect id="path-4" x="12" y="12" width="23" height="43"></rect> <mask id="mask-5" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="23" height="43" fill="white"> <use xlink:href="#path-4"></use> </mask> <rect id="path-6" x="39" y="12" width="23" height="43"></rect> <mask id="mask-7" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="23" height="43" fill="white"> <use xlink:href="#path-6"></use> </mask> <rect id="path-8" x="66" y="12" width="23" height="43"></rect> <mask id="mask-9" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="23" height="43" fill="white"> <use xlink:href="#path-8"></use> </mask> </defs> <g id="Row-Presets" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="row-3-col"> <mask id="mask-2" fill="white"> <use xlink:href="#path-1"></use> </mask> <use id="BG" fill="#FFFFFF" xlink:href="#path-1"></use> <g id="Rectangle-486-Copy" mask="url(#mask-2)" stroke="#00A0D2" stroke-width="2" fill="url(#linearGradient-3)" fill-opacity="0.15"> <use mask="url(#mask-5)" xlink:href="#path-4"></use> </g> <g id="Rectangle-486-Copy" mask="url(#mask-2)" stroke="#00A0D2" stroke-width="2" fill="url(#linearGradient-3)" fill-opacity="0.15"> <use mask="url(#mask-7)" xlink:href="#path-6"></use> </g> <g id="Rectangle-486-Copy" mask="url(#mask-2)" stroke="#00A0D2" stroke-width="2" fill="url(#linearGradient-3)" fill-opacity="0.15"> <use mask="url(#mask-9)" xlink:href="#path-8"></use> </g> </g> </g> </svg>';
        const col4Svg = '<svg width="100px" height="68px" viewBox="0 0 100 68" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <title>row-4-col</title> <defs> <rect id="path-1" x="0" y="0" width="100" height="68"></rect> <linearGradient x1="0%" y1="0%" x2="106.265625%" y2="109.895844%" id="linearGradient-3"> <stop stop-color="#C8EAF4" stop-opacity="0.208021966" offset="0%"></stop> <stop stop-color="#3DD0FF" offset="100%"></stop> </linearGradient> <rect id="path-4" x="11" y="12" width="16" height="43"></rect> <mask id="mask-5" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="16" height="43" fill="white"> <use xlink:href="#path-4"></use> </mask> <rect id="path-6" x="51" y="12" width="16" height="43"></rect> <mask id="mask-7" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="16" height="43" fill="white"> <use xlink:href="#path-6"></use> </mask> <rect id="path-8" x="71" y="12" width="16" height="43"></rect> <mask id="mask-9" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="16" height="43" fill="white"> <use xlink:href="#path-8"></use> </mask> <rect id="path-10" x="31" y="12" width="16" height="43"></rect> <mask id="mask-11" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="16" height="43" fill="white"> <use xlink:href="#path-10"></use> </mask> </defs> <g id="Row-Presets" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="row-4-col"> <mask id="mask-2" fill="white"> <use xlink:href="#path-1"></use> </mask> <use id="BG" fill="#FFFFFF" xlink:href="#path-1"></use> <g id="Rectangle-486-Copy" mask="url(#mask-2)" stroke="#00A0D2" stroke-width="2" fill="url(#linearGradient-3)" fill-opacity="0.15"> <use mask="url(#mask-5)" xlink:href="#path-4"></use> </g> <g id="Rectangle-486-Copy" mask="url(#mask-2)" stroke="#00A0D2" stroke-width="2" fill="url(#linearGradient-3)" fill-opacity="0.15"> <use mask="url(#mask-7)" xlink:href="#path-6"></use> </g> <g id="Rectangle-486-Copy" mask="url(#mask-2)" stroke="#00A0D2" stroke-width="2" fill="url(#linearGradient-3)" fill-opacity="0.15"> <use mask="url(#mask-9)" xlink:href="#path-8"></use> </g> <g id="Rectangle-486-Copy" mask="url(#mask-2)" stroke="#00A0D2" stroke-width="2" fill="url(#linearGradient-3)" fill-opacity="0.15"> <use mask="url(#mask-11)" xlink:href="#path-10"></use> </g> </g> </g> </svg>';
        const leftSvg = '<svg width="100px" height="68px" viewBox="0 0 100 68" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <title>row-large-left</title> <defs> <rect id="path-1" x="0" y="0" width="100" height="68"></rect> <linearGradient x1="0%" y1="0%" x2="106.265625%" y2="109.895844%" id="linearGradient-3"> <stop stop-color="#C8EAF4" stop-opacity="0.208021966" offset="0%"></stop> <stop stop-color="#3DD0FF" offset="100%"></stop> </linearGradient> <rect id="path-4" x="53" y="12" width="36.016129" height="43"></rect> <mask id="mask-5" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="36.016129" height="43" fill="white"> <use xlink:href="#path-4"></use> </mask> <rect id="path-6" x="-5" y="12" width="54.016129" height="43"></rect> <mask id="mask-7" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="54.016129" height="43" fill="white"> <use xlink:href="#path-6"></use> </mask> </defs> <g id="Row-Presets" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="row-large-left"> <mask id="mask-2" fill="white"> <use xlink:href="#path-1"></use> </mask> <use id="BG" fill="#FFFFFF" xlink:href="#path-1"></use> <g id="Rectangle-486-Copy" mask="url(#mask-2)" stroke="#00A0D2" stroke-width="2" fill="url(#linearGradient-3)" fill-opacity="0.15"> <use mask="url(#mask-5)" xlink:href="#path-4"></use> </g> <g id="Rectangle-486-Copy" mask="url(#mask-2)" stroke="#00A0D2" stroke-width="2" fill="url(#linearGradient-3)" fill-opacity="0.15"> <use mask="url(#mask-7)" xlink:href="#path-6"></use> </g> </g> </g> </svg>';
        const rightSvg = '<svg width="100px" height="68px" viewBox="0 0 100 68" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <title>row-large-right</title> <defs> <rect id="path-1" x="0" y="0" width="100" height="68"></rect> <linearGradient x1="0%" y1="0%" x2="106.265625%" y2="109.895844%" id="linearGradient-3"> <stop stop-color="#C8EAF4" stop-opacity="0.208021966" offset="0%"></stop> <stop stop-color="#3DD0FF" offset="100%"></stop> </linearGradient> <rect id="path-4" x="11" y="12" width="36.016129" height="43"></rect> <mask id="mask-5" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="36.016129" height="43" fill="white"> <use xlink:href="#path-4"></use> </mask> <rect id="path-6" x="50.983871" y="12" width="54.016129" height="43"></rect> <mask id="mask-7" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="54.016129" height="43" fill="white"> <use xlink:href="#path-6"></use> </mask> </defs> <g id="Row-Presets" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="row-large-right"> <mask id="mask-2" fill="white"> <use xlink:href="#path-1"></use> </mask> <use id="BG" fill="#FFFFFF" xlink:href="#path-1"></use> <g id="Rectangle-486-Copy" mask="url(#mask-2)" stroke="#00A0D2" stroke-width="2" fill="url(#linearGradient-3)" fill-opacity="0.15"> <use mask="url(#mask-5)" xlink:href="#path-4"></use> </g> <g id="Rectangle-486-Copy" mask="url(#mask-2)" stroke="#00A0D2" stroke-width="2" fill="url(#linearGradient-3)" fill-opacity="0.15"> <use mask="url(#mask-7)" xlink:href="#path-6"></use> </g> </g> </g> </svg>';

        bm.add('row-1', {
            label: '1 Column',
            category: 'ROW',
            content: '<div class="flex flex-col md:flex-row w-full gap-4 py-4" data-gjs-name="Row"><div class="flex-1 min-h-[50px] p-2" data-gjs-name="Col"></div></div>',
            media: col1Svg
        });
        bm.add('row-2', {
            label: '2 Columns',
            category: 'ROW',
            content: '<div class="flex flex-col md:flex-row w-full gap-4 py-4" data-gjs-name="Row"><div class="flex-1 min-h-[50px] p-2" data-gjs-name="Col"></div><div class="flex-1 min-h-[50px] p-2" data-gjs-name="Col"></div></div>',
            media: col2Svg
        });
        bm.add('row-3', {
            label: '3 Columns',
            category: 'ROW',
            content: '<div class="flex flex-col md:flex-row w-full gap-4 py-4" data-gjs-name="Row"><div class="flex-1 min-h-[50px] p-2" data-gjs-name="Col"></div><div class="flex-1 min-h-[50px] p-2" data-gjs-name="Col"></div><div class="flex-1 min-h-[50px] p-2" data-gjs-name="Col"></div></div>',
            media: col3Svg
        });
        bm.add('row-4', {
            label: '4 Columns',
            category: 'ROW',
            content: '<div class="flex flex-col md:flex-row w-full gap-4 py-4" data-gjs-name="Row"><div class="flex-1 min-h-[50px] p-2" data-gjs-name="Col"></div><div class="flex-1 min-h-[50px] p-2" data-gjs-name="Col"></div><div class="flex-1 min-h-[50px] p-2" data-gjs-name="Col"></div><div class="flex-1 min-h-[50px] p-2" data-gjs-name="Col"></div></div>',
            media: col4Svg
        });
        bm.add('row-large-left', {
            label: 'Large Left',
            category: 'ROW',
            content: '<div class="flex flex-col md:flex-row w-full gap-4 py-4" data-gjs-name="Row"><div class="w-full md:w-2/3 min-h-[50px] p-2" data-gjs-name="Col"></div><div class="w-full md:w-1/3 min-h-[50px] p-2" data-gjs-name="Col"></div></div>',
            media: leftSvg
        });
        bm.add('row-large-right', {
            label: 'Large Right',
            category: 'ROW',
            content: '<div class="flex flex-col md:flex-row w-full gap-4 py-4" data-gjs-name="Row"><div class="w-full md:w-1/3 min-h-[50px] p-2" data-gjs-name="Col"></div><div class="w-full md:w-2/3 min-h-[50px] p-2" data-gjs-name="Col"></div></div>',
            media: rightSvg
        });

        // SECTION BLOCKS
        const secDefaultSvg = '<svg width="100px" height="68px" viewBox="0 0 100 68" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <title>section</title> <defs> <rect id="path-1" x="0" y="0" width="100" height="68"></rect> <linearGradient x1="0%" y1="0%" x2="33.1163488%" y2="7.93698705%" id="linearGradient-3"> <stop stop-color="#97C3D1" stop-opacity="0.208021966" offset="0%"></stop> <stop stop-color="#3DD0FF" offset="100%"></stop> </linearGradient> <rect id="path-4" x="7.72046172" y="5.49544863" width="83" height="57"></rect> <mask id="mask-5" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="83" height="57" fill="white"> <use xlink:href="#path-4"></use> </mask> <mask id="mask-7" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="83" height="57" fill="white"> <use xlink:href="#path-4"></use> </mask> <rect id="path-8" x="15.9036217" y="18.0285419" width="19.9352555" height="32.9589345"></rect> <mask id="mask-9" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="19.9352555" height="32.9589345" fill="white"> <use xlink:href="#path-8"></use> </mask> <rect id="path-10" x="40.2139584" y="18.0285419" width="19.9352555" height="32.9589345"></rect> <mask id="mask-11" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="19.9352555" height="32.9589345" fill="white"> <use xlink:href="#path-10"></use> </mask> <rect id="path-12" x="63.8996931" y="18.0285419" width="19.9352555" height="32.9589345"></rect> <mask id="mask-13" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="19.9352555" height="32.9589345" fill="white"> <use xlink:href="#path-12"></use> </mask> </defs> <g id="Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="section"> <mask id="mask-2" fill="white"> <use xlink:href="#path-1"></use> </mask> <use id="BG" fill="#FFFFFF" xlink:href="#path-1"></use> <mask id="mask-6" fill="white"> <use xlink:href="#path-4"></use> </mask> <g id="Rectangle-486-Copy-4" stroke="#00A0D2" mask="url(#mask-5)" stroke-width="2" fill-opacity="0.15" fill="url(#linearGradient-3)"> <use mask="url(#mask-7)" xlink:href="#path-4"></use> </g> <path d="M60.7389551,41.992407 C60.7389551,41.992407 65.2648585,37.713154 66.2506731,31.9582102 C68.9037888,31.9582102 70.542478,25.8399808 67.8893622,23.6878272 C68.0012093,21.4229299 71.2993963,5.90186889 54.5925703,5.90186889 C37.8857442,5.90186889 41.1839312,21.4229299 41.2957783,23.6878272 C38.6426625,25.8399808 40.2813517,31.9582102 42.9344674,31.9582102 C43.920282,37.713154 48.4487865,41.992407 48.4487865,41.992407 C48.4487865,41.992407 48.4123712,46.0386562 46.8725236,46.27166 C41.9096364,47.0257906 23.3794435,54.8301659 23.3794435,63.3886719 L85.805697,63.3886719 C85.805697,54.8301659 67.2755041,47.0257906 62.315218,46.27166 C60.7753704,46.0386562 60.7389551,41.992407 60.7389551,41.992407 Z" id="Shape-Copy-6" fill="#00A0D2" opacity="0.141965951" mask="url(#mask-6)"></path> <g id="Rectangle-486-Copy-2" opacity="0.301131063" mask="url(#mask-6)" stroke="#00A0D2" stroke-width="2" fill="#FFFFFF" fill-opacity="0.0336574389"> <use mask="url(#mask-9)" xlink:href="#path-8"></use> </g> <g id="Rectangle-486-Copy-3" opacity="0.301131063" mask="url(#mask-6)" stroke="#00A0D2" stroke-width="2" fill="#FFFFFF" fill-opacity="0.0336574389"> <use mask="url(#mask-11)" xlink:href="#path-10"></use> </g> <g id="Rectangle-486-Copy-4" opacity="0.301131063" mask="url(#mask-6)" stroke="#00A0D2" stroke-width="2" fill="#FFFFFF" fill-opacity="0.0336574389"> <use mask="url(#mask-13)" xlink:href="#path-12"></use> </g> </g> </g> </svg>';
        const secDarkSvg = '<svg viewBox="0 0 24 24"><path fill="#1f2937" d="M2 4h20v16H2z"/><path fill="#4b5563" d="M6 10h12v2H6zm4 4h4v2h-4z"/></svg>';
        const mediaLeftSvg = '<svg viewBox="0 0 24 24"><path fill="#f3f4f6" d="M2 4h20v16H2z"/><path fill="#9ca3af" d="M4 6h6v12H4z"/><path fill="#cbd5e1" d="M12 10h8v2h-8zm0 4h6v2h-6z"/></svg>';
        const mediaRightSvg = '<svg viewBox="0 0 24 24"><path fill="#f3f4f6" d="M2 4h20v16H2z"/><path fill="#9ca3af" d="M14 6h6v12h-6z"/><path fill="#cbd5e1" d="M4 10h8v2H4zm0 4h6v2H4z"/></svg>';
        const boxLeftSvg = '<svg viewBox="0 0 24 24"><path fill="#d1d5db" d="M2 4h20v16H2z"/><path fill="#fff" d="M4 8h7v8H4z"/><path fill="#9ca3af" d="M13 10h5v2h-5zm0 4h4v2h-4z"/></svg>';
        const boxRightSvg = '<svg viewBox="0 0 24 24"><path fill="#d1d5db" d="M2 4h20v16H2z"/><path fill="#fff" d="M13 8h7v8h-7z"/><path fill="#9ca3af" d="M4 10h5v2H4zm0 4h4v2H4z"/></svg>';

        bm.add('sec-default', {
            label: 'Default',
            category: 'SECTION',
            content: '<section class="py-16 bg-white"><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center"><h2 class="text-3xl font-bold text-gray-900 mb-4">Section Title</h2><p class="text-lg text-gray-600 max-w-2xl mx-auto">This is a default light section with some centered text.</p></div></section>',
            media: secDefaultSvg
        });
        bm.add('sec-dark', {
            label: 'Default Dark',
            category: 'SECTION',
            content: '<section class="py-16 bg-gray-900"><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center"><h2 class="text-3xl font-bold text-white mb-4">Section Title</h2><p class="text-lg text-gray-300 max-w-2xl mx-auto">This is a dark section with some centered text.</p></div></section>',
            media: secDarkSvg
        });
        bm.add('sec-media-left', {
            label: 'Media Left',
            category: 'SECTION',
            content: '<section class="py-16 bg-gray-50"><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center gap-12"><div class="w-full md:w-1/2"><img src="https://via.placeholder.com/600x400" alt="Placeholder" class="w-full h-auto rounded-lg shadow-lg"></div><div class="w-full md:w-1/2"><h2 class="text-3xl font-bold text-gray-900 mb-4">Engaging Title Here</h2><p class="text-lg text-gray-600 mb-6">Describe the feature or product here. This layout is perfect for showcasing images alongside descriptive text.</p><a href="#" class="inline-block bg-blue-600 text-white font-semibold px-6 py-3 rounded-md hover:bg-blue-700">Learn More</a></div></div></section>',
            media: mediaLeftSvg
        });
        bm.add('sec-media-right', {
            label: 'Media Right',
            category: 'SECTION',
            content: '<section class="py-16 bg-white"><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col-reverse md:flex-row items-center gap-12"><div class="w-full md:w-1/2"><h2 class="text-3xl font-bold text-gray-900 mb-4">Engaging Title Here</h2><p class="text-lg text-gray-600 mb-6">Describe the feature or product here. This layout is perfect for showcasing images alongside descriptive text.</p><a href="#" class="inline-block bg-blue-600 text-white font-semibold px-6 py-3 rounded-md hover:bg-blue-700">Learn More</a></div><div class="w-full md:w-1/2"><img src="https://via.placeholder.com/600x400" alt="Placeholder" class="w-full h-auto rounded-lg shadow-lg"></div></div></section>',
            media: mediaRightSvg
        });
        bm.add('sec-box-left', {
            label: 'Box Left',
            category: 'SECTION',
            content: '<section class="relative py-24 bg-gray-200" style="background-image: url(\'https://via.placeholder.com/1200x600\'); background-size: cover; background-position: center;"><div class="absolute inset-0 bg-black bg-opacity-40"></div><div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex"><div class="bg-white p-8 md:p-12 rounded-lg shadow-xl max-w-lg w-full"><h2 class="text-3xl font-bold text-gray-900 mb-4">Box Content Left</h2><p class="text-gray-600 mb-6">This section features a background image with a solid content box on the left side.</p><button class="bg-blue-600 text-white px-6 py-2 rounded">Action</button></div></div></section>',
            media: boxLeftSvg
        });
        bm.add('sec-box-right', {
            label: 'Box Right',
            category: 'SECTION',
            content: '<section class="relative py-24 bg-gray-200" style="background-image: url(\'https://via.placeholder.com/1200x600\'); background-size: cover; background-position: center;"><div class="absolute inset-0 bg-black bg-opacity-40"></div><div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-end"><div class="bg-white p-8 md:p-12 rounded-lg shadow-xl max-w-lg w-full"><h2 class="text-3xl font-bold text-gray-900 mb-4">Box Content Right</h2><p class="text-gray-600 mb-6">This section features a background image with a solid content box on the right side.</p><button class="bg-blue-600 text-white px-6 py-2 rounded">Action</button></div></div></section>',
            media: boxRightSvg
        });

        // CUSTOM TRAITS
        editor.TraitManager.addType('media-picker-trait', {
            createInput({ trait }) {
                const el = document.createElement('div');
                el.innerHTML = `
                    <div style="display: flex; gap: 5px; align-items: stretch; width: 100%;">
                        <input class="gjs-field gjs-sm-property" style="flex:1" type="text" placeholder="URL">
                        <button class="gjs-btn-prim" type="button" style="padding: 0 10px; cursor: pointer; white-space: nowrap; border: 1px solid rgba(0,0,0,0.2); background: rgba(0,0,0,0.1);">Pilih</button>
                    </div>
                `;
                const input = el.querySelector('input');
                const btn = el.querySelector('button');
                input.value = trait.get('value') || '';

                input.addEventListener('change', ev => trait.set('value', ev.target.value));

                btn.addEventListener('click', () => {
                    const am = editor.AssetManager;
                    btn.innerText = '...';
                    
                    let apiUrl = "{!! route('superuser.media.api') !!}?type=image";
                    // Jika halaman dimuat dengan HTTPS, pastikan URL API juga HTTPS (mengatasi masalah Mixed Content / Failed to fetch di cPanel/Cloudflare)
                    if (window.location.protocol === 'https:' && apiUrl.startsWith('http:')) {
                        apiUrl = apiUrl.replace('http:', 'https:');
                    }
                    
                    fetch(apiUrl)
                        .then(r => {
                            if(!r.ok) throw new Error('HTTP ' + r.status);
                            return r.json();
                        })
                        .then(data => {
                            btn.innerText = 'Pilih';
                            if(data.files) {
                                try {
                                    am.add(data.files.map(f => f.url));
                                } catch(e) {
                                    console.error('Asset Manager Add Error:', e);
                                }
                            }
                            am.open({
                                select(asset, complete) {
                                    input.value = asset.getSrc();
                                    trait.set('value', asset.getSrc());
                                    if (complete) am.close();
                                }
                            });
                        })
                        .catch((err) => {
                            btn.innerText = 'Pilih';
                            console.error('Fetch Media Error:', err);
                            alert('Gagal memuat media: ' + err.message);
                        });
                });

                return el;
            }
        });

        editor.TraitManager.addType('content-editor', {
            createInput({ trait }) {
                const el = document.createElement('div');
                el.innerHTML = `
                    <textarea class="gjs-field gjs-sm-property" rows="6" style="width: 100%; min-height: 120px; resize: vertical; padding: 8px; color: #fff; background: rgba(0,0,0,0.2); border: 1px solid rgba(0,0,0,0.3); font-family: monospace; font-size: 12px; border-radius: 3px;"></textarea>
                    <button type="button" class="gjs-btn-prim" style="width: 100%; margin-top: 5px; padding: 5px; cursor: pointer; border-radius: 3px;">Update HTML</button>
                `;
                const input = el.querySelector('textarea');
                const btn = el.querySelector('button');
                const comp = trait.target;
                
                // Set initial value
                input.value = comp.components().models.length > 0 ? comp.getInnerHTML() : (comp.get('content') || '');

                const updateContent = () => {
                    comp.components(input.value);
                };
                
                input.addEventListener('change', updateContent);
                btn.addEventListener('click', updateContent);

                // Listen to inline edits to update textarea
                comp.on('change:content', () => {
                    input.value = comp.components().models.length > 0 ? comp.getInnerHTML() : (comp.get('content') || '');
                });

                return el;
            }
        });

        // UX TEXT COMPONENT
        editor.Components.addType('ux-text', {
            extend: 'text',
            model: {
                defaults: {
                    traits: [
                        { type: 'content-editor', name: 'content', label: 'HTML Editor' },
                        { type: 'number', name: 'style-font-size', label: 'Font size (rem)', step: 0.1 },
                        { type: 'number', name: 'style-line-height', label: 'Line height', step: 0.1 },
                        { 
                            type: 'select', 
                            name: 'style-text-align', 
                            label: 'Text align',
                            options: [
                                { id: '', name: 'Default' },
                                { id: 'left', name: 'Left' },
                                { id: 'center', name: 'Center' },
                                { id: 'right', name: 'Right' },
                                { id: 'justify', name: 'Justify' }
                            ]
                        },
                        { type: 'color', name: 'style-text-color', label: 'Text color' }
                    ]
                },
                init() {
                    this.on('change:attributes:style-font-size', this.handleStyleChange);
                    this.on('change:attributes:style-line-height', this.handleStyleChange);
                    this.on('change:attributes:style-text-align', this.handleStyleChange);
                    this.on('change:attributes:style-text-color', this.handleStyleChange);
                },
                handleStyleChange() {
                    const attrs = this.getAttributes();
                    const style = {};
                    if (attrs['style-font-size']) style['font-size'] = attrs['style-font-size'] + 'rem';
                    if (attrs['style-line-height']) style['line-height'] = attrs['style-line-height'];
                    if (attrs['style-text-align']) style['text-align'] = attrs['style-text-align'];
                    if (attrs['style-text-color']) style['color'] = attrs['style-text-color'];
                    this.addStyle(style);
                }
            }
        });

        // SWIPER SLIDER COMPONENT
        editor.Components.addType('swiper-slider', {
            model: {
                defaults: {
                    script: function() {
                        const initSwiper = () => {
                            if (typeof Swiper === 'undefined') {
                                setTimeout(initSwiper, 100);
                                return;
                            }
                            new Swiper(this, {
                                navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                                pagination: { el: '.swiper-pagination', clickable: true },
                                loop: true
                            });
                        };
                        initSwiper();
                    },
                    traits: [
                        { type: 'checkbox', name: 'loop', label: 'Loop' }
                    ]
                }
            }
        });

        // UX BANNER COMPONENT
        editor.Components.addType('ux-banner-type', {
            model: {
                defaults: {
                    traits: [
                        { type: 'text', name: 'data-height', label: 'Height', placeholder: '400px' },
                        { type: 'media-picker-trait', name: 'data-bg-image', label: 'Image URL' },
                        { type: 'color', name: 'data-overlay', label: 'Overlay Color' }
                    ]
                },
                init() {
                    this.on('change:attributes:data-height', this.handleHeight);
                    this.on('change:attributes:data-bg-image', this.handleBg);
                    this.on('change:attributes:data-overlay', this.handleOverlay);
                },
                handleHeight() {
                    this.addStyle({ height: this.getAttributes()['data-height'] });
                },
                handleBg() {
                    this.addStyle({ 'background-image': `url('${this.getAttributes()['data-bg-image']}')` });
                },
                handleOverlay() {
                    const overlay = this.components().models.find(c => c.getClasses().includes('absolute') && c.getClasses().includes('inset-0'));
                    if (overlay) {
                        overlay.addStyle({ 'background-color': this.getAttributes()['data-overlay'] });
                    }
                }
            }
        });

        const sliderSvg = '<svg width="100px" height="68px" viewBox="0 0 100 68" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <title>slider</title> <defs> <rect id="path-1" x="0" y="0" width="100" height="68"></rect> <linearGradient x1="0%" y1="0%" x2="106.265625%" y2="109.895844%" id="linearGradient-3"> <stop stop-color="#C8EAF4" stop-opacity="0.208021966" offset="0%"></stop> <stop stop-color="#3DD0FF" offset="100%"></stop> </linearGradient> <rect id="path-4" x="8" y="5" width="83" height="57"></rect> <mask id="mask-5" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="83" height="57" fill="white"> <use xlink:href="#path-4"></use> </mask> <mask id="mask-7" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="83" height="57" fill="white"> <use xlink:href="#path-4"></use> </mask> </defs> <g id="Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="slider"> <mask id="mask-2" fill="white"> <use xlink:href="#path-1"></use> </mask> <use id="BG" fill="#FFFFFF" xlink:href="#path-1"></use> <mask id="mask-6" fill="white"> <use xlink:href="#path-4"></use> </mask> <g id="Rectangle-486-Copy-4" stroke="#00A0D2" mask="url(#mask-5)" stroke-width="2" fill-opacity="0.15" fill="url(#linearGradient-3)"> <use mask="url(#mask-7)" xlink:href="#path-4"></use> </g> <path d="M65.9329807,39.4451119 C65.9329807,39.4451119 70.8917905,34.7681073 71.9718991,28.4782481 C74.8787877,28.4782481 76.6742188,21.7913364 73.7673303,19.4391426 C73.8898756,16.9637257 77.5035371,0 59.1986888,0 C40.8938406,0 44.5075021,16.9637257 44.6300474,19.4391426 C41.7231588,21.7913364 43.51859,28.4782481 46.4254786,28.4782481 C47.5055872,34.7681073 52.4672469,39.4451119 52.4672469,39.4451119 C52.4672469,39.4451119 52.4273485,43.8674552 50.7402131,44.1221165 C45.3026216,44.9463427 25,53.4761256 25,62.8301348 L93.3973777,62.8301348 C93.3973777,53.4761256 73.0947561,44.9463427 67.6600144,44.1221165 C65.9728791,43.8674552 65.9329807,39.4451119 65.9329807,39.4451119 Z" id="Shape-Copy-6" fill="#00A0D2" opacity="0.141965951" mask="url(#mask-6)"></path> <circle id="Oval-1" fill="#00A0D2" mask="url(#mask-6)" cx="40.5" cy="34.5" r="3.5"></circle> <circle id="Oval-1" fill-opacity="0.532382246" fill="#00A0D2" opacity="0.333430504" mask="url(#mask-6)" cx="50.5" cy="34.5" r="3.5"></circle> <circle id="Oval-1" fill-opacity="0.532382246" fill="#00A0D2" opacity="0.333430504" mask="url(#mask-6)" cx="60.5" cy="34.5" r="3.5"></circle> </g> </g> </svg>';

        bm.add('custom-slider', {
            label: 'Slider',
            category: 'CONTENT',
            content: `
                <div data-gjs-type="swiper-slider" class="swiper w-full h-[400px] bg-gray-200 overflow-hidden relative">
                    <div class="swiper-wrapper flex">
                        <div class="swiper-slide flex-shrink-0 w-full h-full flex flex-col items-center justify-center bg-blue-600 text-white p-8">
                            <h2 class="text-4xl font-bold mb-4">Slide 1</h2>
                            <p class="text-lg">Edit this slide content</p>
                        </div>
                        <div class="swiper-slide flex-shrink-0 w-full h-full flex flex-col items-center justify-center bg-red-600 text-white p-8">
                            <h2 class="text-4xl font-bold mb-4">Slide 2</h2>
                            <p class="text-lg">Edit this slide content</p>
                        </div>
                        <div class="swiper-slide flex-shrink-0 w-full h-full flex flex-col items-center justify-center bg-green-600 text-white p-8">
                            <h2 class="text-4xl font-bold mb-4">Slide 3</h2>
                            <p class="text-lg">Edit this slide content</p>
                        </div>
                    </div>
                    <div class="swiper-pagination absolute bottom-4 left-0 w-full text-center z-10"></div>
                    <div class="swiper-button-prev absolute top-1/2 left-4 z-10 transform -translate-y-1/2 text-white cursor-pointer bg-black bg-opacity-30 p-2 rounded-full w-10 h-10 flex items-center justify-center">&#10094;</div>
                    <div class="swiper-button-next absolute top-1/2 right-4 z-10 transform -translate-y-1/2 text-white cursor-pointer bg-black bg-opacity-30 p-2 rounded-full w-10 h-10 flex items-center justify-center">&#10095;</div>
                </div>
            `,
            media: sliderSvg
        });

        // FLATSOME PHASE 2 COMPONENT SVGS
        const uxBannerSvg = '<svg width="100px" height="68px" viewBox="0 0 100 68" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <title>ux_banner</title> <defs> <rect id="path-1" x="0" y="0" width="100" height="68"></rect> <linearGradient x1="0%" y1="0%" x2="106.265625%" y2="109.895844%" id="linearGradient-3"> <stop stop-color="#C8EAF4" stop-opacity="0.208021966" offset="0%"></stop> <stop stop-color="#3DD0FF" offset="100%"></stop> </linearGradient> <rect id="path-4" x="8" y="5" width="83" height="57"></rect> <mask id="mask-5" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="83" height="57" fill="white"> <use xlink:href="#path-4"></use> </mask> <mask id="mask-7" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="83" height="57" fill="white"> <use xlink:href="#path-4"></use> </mask> </defs> <g id="Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="ux_banner"> <mask id="mask-2" fill="white"> <use xlink:href="#path-1"></use> </mask> <use id="bg" fill="#FFFFFF" xlink:href="#path-1"></use> <mask id="mask-6" fill="white"> <use xlink:href="#path-4"></use> </mask> <g id="Rectangle-486-Copy-4" stroke="#00A0D2" mask="url(#mask-5)" stroke-width="2" fill-opacity="0.15" fill="url(#linearGradient-3)"> <use mask="url(#mask-7)" xlink:href="#path-4"></use> </g> <path d="M79.2164119,40.3544812 C79.2164119,40.3544812 84.9364256,34.7396632 86.1823366,27.1885876 C89.5354481,27.1885876 91.6064876,19.1608443 88.2533761,16.3369984 C88.3947327,13.3652212 92.5631105,-7 71.4483703,-7 C50.3336301,-7 54.5020079,13.3652212 54.6433646,16.3369984 C51.2902531,19.1608443 53.3612925,27.1885876 56.714404,27.1885876 C57.960315,34.7396632 63.6836161,40.3544812 63.6836161,40.3544812 C63.6836161,40.3544812 63.637593,45.6635744 61.6914734,45.9692993 C55.4191825,46.9587959 32,57.1989353 32,68.4285714 L110.896741,68.4285714 C110.896741,57.1989353 87.4775581,46.9587959 81.2085546,45.9692993 C79.262435,45.6635744 79.2164119,40.3544812 79.2164119,40.3544812 Z" id="Shape-Copy-6" fill="#00A0D2" opacity="0.141965951" mask="url(#mask-6)"></path> <g id="text" mask="url(#mask-6)" fill="#00A0D2"> <g transform="translate(21.000000, 19.000000)"> <rect id="Rectangle-166" x="3" y="21" width="20" height="7.05992857" rx="3"></rect> <rect id="Rectangle-167" fill-opacity="0.532382246" opacity="0.333430504" x="0" y="0" width="27" height="6"></rect> <rect id="Rectangle-167-Copy" fill-opacity="0.532382246" opacity="0.333430504" x="2" y="9" width="23" height="2"></rect> <rect id="Rectangle-167-Copy-2" fill-opacity="0.532382246" opacity="0.333430504" x="5" y="13" width="17" height="2"></rect> </g> </g> </g> </g> </svg>';
        const imageBoxSvg = '<svg width="100px" height="68px" viewBox="0 0 100 68" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <title>image_box</title> <defs> <rect id="path-1" x="0" y="0" width="100" height="68"></rect> <linearGradient x1="0%" y1="0%" x2="106.265625%" y2="109.895844%" id="linearGradient-3"> <stop stop-color="#C8EAF4" stop-opacity="0.208021966" offset="0%"></stop> <stop stop-color="#3DD0FF" offset="100%"></stop> </linearGradient> <rect id="path-4" x="0" y="0" width="64" height="57"></rect> <mask id="mask-5" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="64" height="57" fill="white"> <use xlink:href="#path-4"></use> </mask> <rect id="path-6" x="0" y="0" width="63.4510499" height="36.2789638"></rect> </defs> <g id="Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="image_box"> <mask id="mask-2" fill="white"> <use xlink:href="#path-1"></use> </mask> <use id="BG" fill="#FFFFFF" xlink:href="#path-1"></use> <g id="image" mask="url(#mask-2)"> <g transform="translate(18.000000, 5.000000)"> <use id="Mask" stroke="#00A0D2" mask="url(#mask-5)" stroke-width="2" fill-opacity="0.15" fill="url(#linearGradient-3)" fill-rule="evenodd" xlink:href="#path-4"></use> <mask id="mask-7" fill="white"> <use xlink:href="#path-6"></use> </mask> <use id="Mask" stroke="none" fill="#00A0D2" fill-rule="evenodd" opacity="0.141965951" xlink:href="#path-6"></use> <path d="M36.1818164,21.0739005 C36.1818164,21.0739005 39.5048066,17.8328878 40.2286074,13.4742191 C42.1765672,13.4742191 43.3797188,8.84040593 41.431759,7.21041185 C41.5138789,5.4950281 43.9354603,-6.26028425 31.6690429,-6.26028425 C19.4026255,-6.26028425 21.8242069,5.4950281 21.9063268,7.21041185 C19.958367,8.84040593 21.1615186,13.4742191 23.1094784,13.4742191 C23.8332792,17.8328878 27.1581792,21.0739005 27.1581792,21.0739005 C27.1581792,21.0739005 27.1314425,24.1384411 26.0008619,24.3149131 C22.3570312,24.8860752 8.75186895,30.7969385 8.75186895,37.2789638 L54.5862169,37.2789638 C54.5862169,30.7969385 40.9810546,24.8860752 37.3391337,24.3149131 C36.2085531,24.1384411 36.1818164,21.0739005 36.1818164,21.0739005 Z" id="Shape-Copy-6" stroke="none" fill="#00A0D2" fill-rule="evenodd" opacity="0.141965951" mask="url(#mask-7)"></path> </g> </g> <g id="text-small" opacity="0.6" mask="url(#mask-2)" fill-opacity="0.532382246" fill="#00A0D2"> <g transform="translate(38.000000, 48.000000)" opacity="0.333430504"> <rect id="Rectangle-167-Copy-14" x="0" y="0" width="23" height="2"></rect> <rect id="Rectangle-167-Copy-15" x="3" y="4" width="17" height="2"></rect> </g> </g> </g> </g> </svg>';
        const iconBoxSvg = '<svg width="100px" height="68px" viewBox="0 0 100 68" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <title>icon_box</title> <defs> <rect id="path-1" x="0" y="0" width="100" height="68"></rect> </defs> <g id="Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="icon_box"> <mask id="mask-2" fill="white"> <use xlink:href="#path-1"></use> </mask> <use id="BG" fill="#FFFFFF" xlink:href="#path-1"></use> <g id="headline" mask="url(#mask-2)" fill="#00A0D2"> <g transform="translate(18.000000, 20.000000)" id="Rectangle-494"> <rect x="0" y="0" width="15" height="15" rx="7.5"></rect> </g> </g> <g id="headline" opacity="0.6" mask="url(#mask-2)" fill="#00A0D2"> <g transform="translate(39.000000, 21.000000)"> <rect id="Rectangle-167-Copy-16" x="1.3040592" y="0" width="38.7274444" height="3.42038762"></rect> <rect id="Rectangle-167-Copy-17" x="1.3040592" y="6.84077524" width="28.6246328" height="3.42038762"></rect> <rect id="Rectangle-167-Copy-8" fill-opacity="0.532382246" opacity="0.333430504" x="0.965060165" y="21.5376188" width="23.4897265" height="3.19675615"></rect> <rect id="Rectangle-167-Copy-7" fill-opacity="0.532382246" opacity="0.333430504" x="0.965060165" y="14.5247296" width="27.8195262" height="3.19675615"></rect> </g> </g> </g> </g> </svg>';
        const testimonialSvg = '<svg width="100px" height="68px" viewBox="0 0 100 68" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <title>testimonials</title> <defs> <rect id="path-1" x="0" y="0" width="100" height="68"></rect> <linearGradient x1="0%" y1="0%" x2="106.265625%" y2="109.895844%" id="linearGradient-3"> <stop stop-color="#C8EAF4" stop-opacity="0.208021966" offset="0%"></stop> <stop stop-color="#3DD0FF" offset="100%"></stop> </linearGradient> <rect id="path-4" x="0" y="0" width="23.3501953" height="23.3501953" rx="11.6750977"></rect> <mask id="mask-5" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="23.3501953" height="23.3501953" fill="white"> <use xlink:href="#path-4"></use> </mask> <mask id="mask-7" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="23.3501953" height="23.3501953" fill="white"> <use xlink:href="#path-4"></use> </mask> </defs> <g id="Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="testimonials"> <mask id="mask-2" fill="white"> <use xlink:href="#path-1"></use> </mask> <use id="BG" fill="#FFFFFF" xlink:href="#path-1"></use> <g id="headline" mask="url(#mask-2)"> <g transform="translate(11.083008, 13.689859)"> <mask id="mask-6" fill="white"> <use xlink:href="#path-4"></use> </mask> <g id="Rectangle-494" stroke="#00A0D2" mask="url(#mask-5)" stroke-width="2" fill-opacity="0.15" fill="url(#linearGradient-3)" fill-rule="evenodd"> <use mask="url(#mask-7)" xlink:href="#path-4"></use> </g> <path d="M12.9857508,15.8366034 C12.9857508,15.8366034 14.4155106,14.433138 14.7269353,12.5456908 C15.5650704,12.5456908 16.082742,10.5390968 15.244607,9.83325565 C15.2799401,9.09043791 16.321857,4 11.0440713,4 C5.76628553,4 6.80820244,9.09043791 6.84353558,9.83325565 C6.00540053,10.5390968 6.52307218,12.5456908 7.36120723,12.5456908 C7.67263193,14.433138 9.10321343,15.8366034 9.10321343,15.8366034 C9.10321343,15.8366034 9.09170961,17.1636506 8.6052626,17.2400688 C7.03745703,17.4874008 1.18365885,20.0469995 1.18365885,22.8539302 L20.9044837,22.8539302 C20.9044837,20.0469995 15.0506855,17.4874008 13.4837016,17.2400688 C12.9972546,17.1636506 12.9857508,15.8366034 12.9857508,15.8366034 Z" id="Oval-7" stroke="none" fill="#00A0D2" fill-rule="evenodd" mask="url(#mask-6)"></path> </g> </g> <g id="headline" opacity="0.6" mask="url(#mask-2)" fill="#00A0D2"> <g transform="translate(39.000000, 16.000000)"> <rect id="Rectangle-167-Copy-8" fill-opacity="0.532382246" opacity="0.333430504" x="1.57681031" y="29.3319376" width="30.47165" height="4.14693781"></rect> <rect id="Rectangle-167-Copy-7" fill-opacity="0.532382246" opacity="0.333430504" x="1.57681031" y="20.2345858" width="36.0884095" height="4.14693781"></rect> <polygon id="Shape" points="9.66433185 5.26142111 7.45968424 0.794155796 5.25503664 5.26142111 0.324902344 5.97757485 3.89229329 9.45488918 3.04994311 14.3649569 7.45968424 12.0465987 11.8694254 14.3649569 11.0270752 9.45488918 14.5944661 5.97757485"></polygon> <polygon id="Shape" points="27.8255949 5.26142111 25.6209473 0.794155796 23.4162997 5.26142111 18.4861654 5.97757485 22.0535563 9.45488918 21.2112061 14.3649569 25.6209473 12.0465987 30.0306884 14.3649569 29.1883382 9.45488918 32.7557292 5.97757485"></polygon> <polygon id="Shape" points="45.9868579 5.26142111 43.7822103 0.794155796 41.5775627 5.26142111 36.6474284 5.97757485 40.2148193 9.45488918 39.3724691 14.3649569 43.7822103 12.0465987 48.1919514 14.3649569 47.3496012 9.45488918 50.9169922 5.97757485"></polygon> </g> </g> </g> </g> </svg>';
        const wooProductsSvg = '<svg width="100px" height="68px" viewBox="0 0 100 68" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <title>woo_products</title> <defs> <rect id="path-1" x="0" y="0" width="100" height="68"></rect> </defs> <g id="Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="woo_products"> <mask id="mask-2" fill="white"> <use xlink:href="#path-1"></use> </mask> <use id="BG" fill="#FFFFFF" xlink:href="#path-1"></use> <g id="woocommerce" opacity="0.762418377" mask="url(#mask-2)"> <g transform="translate(19.000000, 16.000000)" id="Group"> <g> <path d="M5.84685882,0 L57.1275726,0 C60.3728614,0 63,2.65139576 63,5.92664931 L63,25.682147 C63,28.9574005 60.3728614,31.6087964 57.1275726,31.6087964 L38.7376029,31.6087964 L41.2617164,37.8473746 L30.1607684,31.6087964 L5.87261509,31.6087964 C2.62732631,31.6087964 0.000187778996,28.9574005 0.000187778996,25.682147 L0.000187778996,5.92664931 C-0.0255684811,2.67738982 2.60157004,0 5.84685882,0 L5.84685882,0 L5.84685882,0 Z" id="Shape" fill="#9B5C8F"></path> <path d="M3.58760376,5.40185982 C3.94614033,4.91078165 4.48394522,4.65231946 5.20101838,4.60062702 C6.50711595,4.49724216 7.24979888,5.1175514 7.42906717,6.46155482 C8.2229696,11.8634146 9.09370131,16.4381955 10.0156525,20.1858972 L15.6241891,9.40802381 C16.1363842,8.42586749 16.7766281,7.90894308 17.5449208,7.85725066 C18.6717501,7.77971198 19.3632135,8.50340614 19.6449208,10.0283331 C20.2851647,13.4658802 21.1046769,16.386503 22.0778476,18.8677401 C22.7437013,12.3028003 23.8705306,7.57294224 25.4583354,4.65231946 C25.8424819,3.92862533 26.4058965,3.56677825 27.1485794,3.51508581 C27.7376038,3.46339337 28.2754087,3.64431691 28.7619939,4.03201019 C29.2485793,4.4197035 29.5046768,4.91078165 29.5558965,5.50524471 C29.5815063,5.97047665 29.5046768,6.35816993 29.2997988,6.74586322 C28.3010185,8.606791 27.4815061,11.7341835 26.8156525,16.0763484 C26.1754086,20.2892821 25.9449209,23.571752 26.0985793,25.923758 C26.1497988,26.5699134 26.0473598,27.1385303 25.7912624,27.6296083 C25.4839452,28.1982251 25.0229695,28.5083799 24.4339452,28.5600723 C23.7680916,28.6117647 23.0766281,28.3016102 22.4107745,27.6037621 C20.0290672,25.1483714 18.1339452,21.4782082 16.7510184,16.5932728 C15.0863842,19.9015888 13.857116,22.3828259 13.0632135,24.0369839 C11.5522379,26.9576066 10.2717501,28.4566875 9.19614034,28.5342261 C8.50467693,28.5859185 7.91565252,27.9914554 7.4034574,26.7508369 C6.09735984,23.3649822 4.68882326,16.8258887 3.17784765,7.13355651 C3.07540862,6.46155482 3.22906717,5.86709176 3.58760376,5.40185982 L3.58760376,5.40185982 Z M58.6229697,9.45971625 C57.7010183,7.83140443 56.3437013,6.84924811 54.5254086,6.46155482 C54.0388232,6.35816993 53.5778477,6.30647749 53.1424818,6.30647749 C50.6839452,6.30647749 48.6863843,7.59878847 47.124189,10.1834104 C45.7924817,12.380339 45.1266281,14.8098836 45.1266281,17.4720442 C45.1266281,19.4622031 45.5363843,21.1680536 46.3558964,22.5895956 C47.2778477,24.2179074 48.6351648,25.2000639 50.4534575,25.587757 C50.9400429,25.6911418 51.4010183,25.7428342 51.8363843,25.7428342 C54.3205306,25.7428342 56.3180915,24.4505234 57.8546768,21.8659015 C59.1863843,19.6431266 59.852238,17.213582 59.852238,14.5514214 C59.8778477,12.5354163 59.4424818,10.8554121 58.6229697,9.45971625 L58.6229697,9.45971625 Z M55.3961404,16.619119 C55.0376037,18.3249694 54.3973598,19.5914342 53.449799,20.4443594 C52.7071159,21.1163611 52.0156525,21.4006695 51.3754086,21.2714385 C50.7607744,21.1422074 50.2485793,20.5994367 49.8644331,19.5914342 C49.5571159,18.7902014 49.4034574,17.9889686 49.4034574,17.2394282 C49.4034574,16.5932728 49.4546769,15.9471173 49.5827256,15.3526542 C49.8132136,14.2929592 50.2485793,13.2591105 50.9400429,12.2769541 C51.7851648,11.0104894 52.6815061,10.493565 53.6034575,10.6744886 C54.2180916,10.8037196 54.7302868,11.3464902 55.114433,12.3544928 C55.4217502,13.1557256 55.5754087,13.9569584 55.5754087,14.7064987 C55.5754087,15.3785005 55.5241892,16.0246559 55.3961404,16.619119 L55.3961404,16.619119 Z M42.5912622,9.45971625 C41.6693111,7.83140443 40.2863843,6.84924811 38.4937014,6.46155482 C38.0071159,6.35816993 37.5461403,6.30647749 37.1107746,6.30647749 C34.652238,6.30647749 32.6546768,7.59878847 31.0924818,10.1834104 C29.7607745,12.380339 29.0949209,14.8098836 29.0949209,17.4720442 C29.0949209,19.4622031 29.5046768,21.1680536 30.3241892,22.5895956 C31.2461403,24.2179074 32.6034573,25.2000639 34.42175,25.587757 C34.9083354,25.6911418 35.3693111,25.7428342 35.8046768,25.7428342 C38.2888232,25.7428342 40.2863843,24.4505234 41.8229696,21.8659015 C43.1546769,19.6431266 43.8205305,17.213582 43.8205305,14.5514214 C43.8205305,12.5354163 43.4107746,10.8554121 42.5912622,9.45971625 L42.5912622,9.45971625 Z M39.3388232,16.619119 C38.9802868,18.3249694 38.3400429,19.5914342 37.3924818,20.4443594 C36.6497989,21.1163611 35.9583355,21.4006695 35.3180916,21.2714385 C34.7034575,21.1422074 34.1912623,20.5994367 33.8071159,19.5914342 C33.4997989,18.7902014 33.3461404,17.9889686 33.3461404,17.2394282 C33.3461404,16.5932728 33.3973599,15.9471173 33.5254087,15.3526542 C33.7558964,14.2929592 34.1912623,13.2591105 34.8827257,12.2769541 C35.7278476,11.0104894 36.6241892,10.493565 37.5461403,10.6744886 C38.1607744,10.8037196 38.6729696,11.3464902 39.057116,12.3544928 C39.364433,13.1557256 39.5180915,13.9569584 39.5180915,14.7064987 C39.5437012,15.3785005 39.466872,16.0246559 39.3388232,16.619119 L39.3388232,16.619119 L39.3388232,16.619119 L39.3388232,16.619119 Z" id="Shape" fill="#FFFFFF"></path> </g> </g> </g> </g> </g> </svg>';

        bm.add('ux-banner', {
            label: 'UX Banner',
            category: 'CONTENT',
            content: '<div data-gjs-type="ux-banner-type" data-height="400px" data-bg-image="https://images.unsplash.com/photo-1558244661-d248897f7bc4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" data-overlay="" class="relative w-full h-[400px] bg-gray-300 flex items-center justify-center bg-cover bg-center" style="background-image: url(\'https://images.unsplash.com/photo-1558244661-d248897f7bc4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80\');"><div class="absolute inset-0 bg-black bg-opacity-40 pointer-events-none"></div><div class="relative z-10 text-center text-white p-6 max-w-2xl"><h2 class="text-4xl md:text-5xl font-bold mb-4">Banner Title</h2><p class="text-lg md:text-xl mb-6">Describe your banner here with a captivating subtitle.</p><a href="#" class="inline-block bg-white text-gray-900 font-semibold px-8 py-3 rounded-md hover:bg-gray-100 transition">Shop Now</a></div></div>',
            media: uxBannerSvg
        });

        const uxHotspotSvg = '<svg width="100px" height="68px" viewBox="0 0 100 68" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <title>ux_hotspot</title> <defs> <rect id="path-1" x="0" y="0" width="100" height="68"></rect> </defs> <g id="Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="ux_hotspot"> <mask id="mask-2" fill="white"> <use xlink:href="#path-1"></use> </mask> <use id="bg" fill="#FFFFFF" xlink:href="#path-1"></use> <circle id="Oval-12" fill="#00A0D2" mask="url(#mask-2)" cx="49" cy="34" r="17.078125"></circle> <polygon id="+" fill="#FFFFFF" mask="url(#mask-2)" points="50.2238076 25.4609375 50.2238076 32.8408426 57.2580497 32.8408426 57.2580497 35.1740679 50.2238076 35.1740679 50.2238076 42.6058224 47.7004677 42.6058224 47.7004677 35.1740679 40.7007919 35.1740679 40.7007919 32.8408426 47.7004677 32.8408426 47.7004677 25.4609375"></polygon> </g> </g> </svg>';

        bm.add('ux-hotspot', {
            label: 'Hotspot',
            category: 'CONTENT',
            content: '<div class="relative inline-block"><img src="https://images.unsplash.com/photo-1522204523234-8729aa6e3d5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Hotspot Image" class="w-full max-w-2xl rounded-lg shadow-md"><div class="absolute top-[30%] left-[40%] group cursor-pointer"><div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xl font-bold shadow-lg animate-pulse hover:animate-none">+</div><div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-48 bg-white p-3 rounded shadow-xl opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"><h4 class="font-bold text-gray-900 text-sm mb-1">Hotspot Product</h4><p class="text-xs text-gray-600 mb-2">Detailed info about this particular spot.</p><span class="block text-blue-600 font-bold text-sm">Rp 120.000</span></div></div></div>',
            media: uxHotspotSvg
        });

        bm.add('image-box', {
            label: 'Image Box',
            category: 'CONTENT',
            content: '<div class="group text-center"><div class="overflow-hidden rounded-lg mb-4"><img src="https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Image Box" class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-500"></div><h3 class="text-lg font-bold text-gray-900 mb-2">Image Box Title</h3><p class="text-gray-600">A short description for this image box.</p></div>',
            media: imageBoxSvg
        });

        bm.add('icon-box', {
            label: 'Icon Box',
            category: 'CONTENT',
            content: '<div class="flex flex-col items-center text-center p-6 bg-white rounded-lg border border-gray-100 hover:shadow-lg transition-shadow"><div class="w-16 h-16 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mb-4"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></div><h3 class="text-xl font-bold text-gray-900 mb-2">Icon Box Title</h3><p class="text-gray-600">Icon box description goes here. It is great for features.</p></div>',
            media: iconBoxSvg
        });

        bm.add('testimonial', {
            label: 'Testimonial',
            category: 'CONTENT',
            content: '<div class="bg-gray-50 p-8 rounded-xl text-center"><div class="text-yellow-400 mb-4 flex justify-center space-x-1"><svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg><svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg><svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg><svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg><svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg></div><p class="text-xl text-gray-700 italic mb-6">"Desain dan pelayanannya sangat memuaskan, sangat direkomendasikan!"</p><div class="flex flex-col items-center"><img src="https://ui-avatars.com/api/?name=John+Doe&background=random" alt="Avatar" class="w-14 h-14 rounded-full mb-2"><h4 class="font-bold text-gray-900">John Doe</h4><span class="text-sm text-gray-500">Customer</span></div></div>',
            media: testimonialSvg
        });

        bm.add('ux-text', {
            label: 'UX Text Editor',
            category: 'CONTENT',
            content: '<div data-gjs-type="ux-text" class="text-gray-900 leading-relaxed"><h3 class="uppercase text-2xl mb-2"><strong>This is a simple banner</strong></h3><p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p></div>',
            media: '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M2.5 4v3h5v12h3V7h5V4h-13z"/></svg>'
        });

        bm.add('woo-products', {
            label: 'Products Grid',
            category: 'SHOP',
            content: {!! json_encode('<div class="grid grid-cols-2 md:grid-cols-4 gap-6">' . 
                (isset($products) && $products->count() > 0 ? $products->map(function($product) {
                    $img = $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : 'https://via.placeholder.com/600';
                    $price = 'Rp ' . number_format($product->price ?? 0, 0, ',', '.');
                    $title = htmlspecialchars($product->name);
                    $category = htmlspecialchars($product->category->name ?? 'Category');
                    return '<div class="group"><div class="w-full aspect-square bg-gray-100 mb-4 overflow-hidden relative"><img src="' . $img . '" alt="' . $title . '" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"><div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-opacity flex items-end justify-center pb-4 opacity-0 group-hover:opacity-100"><button class="bg-white text-gray-900 font-bold px-4 py-2 rounded shadow-sm text-sm uppercase tracking-wider hover:bg-gray-900 hover:text-white transition">Quick View</button></div></div><h3 class="text-sm font-semibold text-gray-900 mb-1">' . $title . '</h3><p class="text-gray-500 text-sm mb-2">' . $category . '</p><span class="font-bold text-gray-900">' . $price . '</span></div>';
                })->implode('') : '<div class="col-span-full text-center py-8 text-gray-500">No products found.</div>') . 
                '</div>') !!},
            media: wooProductsSvg
        });

        const tabsSvg = '<svg width="100px" height="68px" viewBox="0 0 100 68" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <title>tabs</title> <defs> <rect id="path-1" x="0" y="0" width="100" height="68"></rect> <linearGradient x1="0%" y1="0%" x2="106.265625%" y2="109.895844%" id="linearGradient-3"> <stop stop-color="#C8EAF4" stop-opacity="0.208021966" offset="0%"></stop> <stop stop-color="#3DD0FF" offset="100%"></stop> </linearGradient> <path d="M50,2.9996277 C50,1.34297906 51.3433937,-2.13162821e-14 52.9936145,-2.13162821e-14 L94.0063855,-2.13162821e-14 C95.6597131,-2.13162821e-14 97,1.34963851 97,2.9996277 L97,22.9447679 L50,22.9447679 L50,2.9996277 Z" id="path-4"></path> <mask id="mask-5" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="47" height="22.9447679" fill="white"> <use xlink:href="#path-4"></use> </mask> <path d="M0,2.9996277 C0,1.34297906 1.34339372,-2.13162821e-14 2.99361448,-2.13162821e-14 L44.0063855,-2.13162821e-14 C45.6597131,-2.13162821e-14 47,1.34963851 47,2.9996277 L47,22.9447679 L0,22.9447679 L0,2.9996277 Z" id="path-6"></path> <mask id="mask-7" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="47" height="22.9447679" fill="white"> <use xlink:href="#path-6"></use> </mask> <path d="M100,2.9996277 C100,1.34297906 101.343394,-2.13162821e-14 102.993614,-2.13162821e-14 L144.006386,-2.13162821e-14 C145.659713,-2.13162821e-14 147,1.34963851 147,2.9996277 L147,22.9447679 L100,22.9447679 L100,2.9996277 Z" id="path-8"></path> <mask id="mask-9" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="47" height="22.9447679" fill="white"> <use xlink:href="#path-8"></use> </mask> </defs> <g id="Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="tabs"> <mask id="mask-2" fill="white"> <use xlink:href="#path-1"></use> </mask> <use id="BG" fill="#FFFFFF" xlink:href="#path-1"></use> <g id="Rectangle-166-Copy-+-Shape-Copy-18" mask="url(#mask-2)"> <g transform="translate(-22.000000, 21.000000)"> <use id="Rectangle-166-Copy" stroke="#00A0D2" mask="url(#mask-5)" stroke-width="2" fill-opacity="0.15" fill="url(#linearGradient-3)" xlink:href="#path-4"></use> <use id="Rectangle-166-Copy" stroke="#00A0D2" mask="url(#mask-7)" stroke-width="2" fill-opacity="0.0336574389" fill="#FFFFFF" opacity="0.12943097" xlink:href="#path-6"></use> <use id="Rectangle-166-Copy" stroke="#00A0D2" mask="url(#mask-9)" stroke-width="2" fill-opacity="0.0336574389" fill="#FFFFFF" opacity="0.12943097" xlink:href="#path-8"></use> <path d="M83,19.1205776 C83,17.7725725 82.8739267,19.4532484 82.5652041,18.1769456 C82.3391445,17.2974261 81.683058,16.6042879 80.826102,16.6042879 C80.3091323,16.6042879 79.8391598,16.8528712 79.5217551,17.2501025 L79.5217551,17.0823189 C79.5217551,16.0259185 78.7435083,15.17024 77.7826122,15.17024 C76.8217162,15.17024 76.0434694,16.0258737 76.0434694,17.0823189 L76.0434694,16.1263018 C76.0434694,15.0699014 75.2652226,14.2142229 74.3043266,14.2142229 C73.3434305,14.2142229 72.5651837,15.0698566 72.5651837,16.1263018 L72.5652245,9.91207893 C72.5652245,8.85567851 71.7869777,8 70.8260816,8 C69.8651856,8 69.0869388,8.8556337 69.0869388,9.91207893 L69.0869388,20.8160869 C69.0869388,21.3461917 68.6956347,21.772104 68.2173878,21.772104 C68.1347655,21.772104 68.0565047,21.7577635 67.9782439,21.7338776 L65.5217102,20.6344558 C65.2869686,20.5048985 65.0217785,20.428401 64.7391428,20.428401 C63.7782875,20.428401 63,21.2840347 63,22.3404799 C63,23.1340014 63.443478,23.8175493 64.0695645,24.109154 C66.8913153,25.7104927 69.1521561,27.6751384 70.7347774,30.9447679 L80.6700288,30.9447679 C82.1695794,28.2774386 83,22.179402 83,19.1205776 Z" id="Shape-Copy-18" stroke="none" fill="#00A0D2"></path> </g> </g> </g> </g> </svg>';
        
        bm.add('tabs', {
            label: 'Tabs',
            category: 'CONTENT',
            content: '<div class="w-full"><div class="flex border-b border-gray-200"><button class="px-6 py-3 font-semibold text-blue-600 border-b-2 border-blue-600">Tab 1</button><button class="px-6 py-3 font-medium text-gray-500 hover:text-gray-700">Tab 2</button><button class="px-6 py-3 font-medium text-gray-500 hover:text-gray-700">Tab 3</button></div><div class="p-6 bg-white border border-t-0 border-gray-200"><p class="text-gray-600">Content for the first tab. Edit this text and add elements here.</p></div></div>',
            media: tabsSvg
        });

        // Load project data if exists
        if (savedData) {
            try {
                editor.loadProjectData(JSON.parse(savedData));
            } catch(e) {
                console.error("Error loading builder data", e);
            }
        }

        document.getElementById('save-btn').addEventListener('click', function() {
            const btn = this;
            const originalText = btn.innerHTML;
            btn.innerHTML = 'Saving...';
            btn.disabled = true;

            const html = editor.getHtml();
            const css = editor.getCss();
            const projectData = editor.getProjectData();

            const formData = new FormData();
            formData.append('html', html);
            formData.append('css', css);
            formData.append('builder_data', JSON.stringify(projectData));

            fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(async response => {
                if (!response.ok) {
                    const text = await response.text();
                    throw new Error(`HTTP ${response.status}: ${text.substring(0, 100)}...`);
                }
                return response.json();
            })
            .then(data => {
                showToast(data.message || 'Page successfully saved.');
            })
            .catch(error => {
                console.error('Error details:', error);
                alert("Gagal menyimpan!\nDetail: " + error.message);
                showToast('Failed to save. See alert.', true);
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        });

        function showToast(message, isError = false) {
            const toast = document.getElementById('toast');
            document.getElementById('toast-msg').innerText = message;
            
            if(isError) {
                toast.querySelector('svg').classList.replace('text-green-400', 'text-red-400');
            } else {
                toast.querySelector('svg').classList.replace('text-red-400', 'text-green-400');
            }

            toast.classList.remove('translate-y-20', 'opacity-0');
            
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
            }, 3000);
        }
    </script>
</body>
</html>
