<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UX Builder - {{ $page->title }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
        #gjs { height: calc(100vh - 50px); overflow: hidden; }
        /* Layout Customization (Left Sidebar) */
        .gjs-cv-canvas {
            width: calc(100% - 280px) !important;
            left: 280px !important;
            right: auto !important;
        }
        .gjs-pn-views-container {
            left: 40px !important;
            right: auto !important;
            width: 240px !important;
            border-right: 1px solid #333;
            box-shadow: 2px 0 5px rgba(0,0,0,0.2);
        }
        .gjs-pn-views {
            left: 0 !important;
            right: auto !important;
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

        const col1Svg = '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M2 4h20v16H2V4zm2 2v12h16V6H4z"/></svg>';
        const col2Svg = '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M2 4h9v16H2V4zm11 0h9v16h-9V4z"/></svg>';
        const col3Svg = '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M2 4h6v16H2V4zm7 0h6v16H9V4zm7 0h6v16h-6V4z"/></svg>';
        const col4Svg = '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M2 4h4v16H2V4zm5 0h4v16H7V4zm5 0h4v16h-4V4zm5 0h4v16h-4V4z"/></svg>';
        const leftSvg = '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M2 4h13v16H2V4zm15 0h5v16h-5V4z"/></svg>';
        const rightSvg = '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M2 4h5v16H2V4zm7 0h13v16H9V4z"/></svg>';

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
        const secDefaultSvg = '<svg viewBox="0 0 24 24"><path fill="#f3f4f6" d="M2 4h20v16H2z"/><path fill="#cbd5e1" d="M6 10h12v2H6zm4 4h4v2h-4z"/></svg>';
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

        const sliderSvg = '<svg viewBox="0 0 24 24"><path fill="#f3f4f6" d="M2 4h20v16H2z"/><path fill="#9ca3af" d="M8 12l8-5v10z"/></svg>';

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
