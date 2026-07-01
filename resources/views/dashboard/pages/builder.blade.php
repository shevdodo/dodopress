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
            background: #f0f0f0;
        }
        .gjs-pn-views-container {
            left: 40px !important;
            right: auto !important;
            width: 240px !important;
            background: #f9f9f9 !important;
            border-right: 1px solid #ddd;
            box-shadow: 2px 0 5px rgba(0,0,0,0.02);
        }
        .gjs-pn-views {
            left: 0 !important;
            right: auto !important;
            background: #1e1e1e !important;
            border-right: none !important;
        }
        
        /* Light Theme for Panels */
        .gjs-block {
            background: #fff !important;
            border: 1px solid #e5e5e5 !important;
            color: #555 !important;
            border-radius: 4px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
            transition: all 0.2s;
        }
        .gjs-block:hover {
            border-color: #00a0d2 !important;
            color: #00a0d2 !important;
            box-shadow: 0 2px 4px rgba(0,160,210,0.1);
        }
        .gjs-block-category .gjs-title, 
        .gjs-sm-sector .gjs-sm-title,
        .gjs-layer-title {
            background: #fff !important;
            color: #333 !important;
            border-bottom: 1px solid #eee !important;
            border-top: 1px solid #eee !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        .gjs-blocks-c, .gjs-sm-properties, .gjs-clm-tags, .gjs-sm-sectors {
            background: #f9f9f9 !important;
        }
        .gjs-sm-property, .gjs-clm-tag {
            color: #444 !important;
        }
        .gjs-field {
            background-color: #fff !important;
            color: #333 !important;
            border: 1px solid #ccc !important;
        }
        .gjs-pn-btn {
            color: #a0a0a0 !important;
        }
        .gjs-pn-btn.gjs-pn-active {
            color: #fff !important;
            background: #00a0d2 !important;
        }
        .gjs-trait {
            color: #333 !important;
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
            plugins: ['gjs-preset-webpage', 'gjs-blocks-basic'],
            pluginsOpts: {
                'gjs-preset-webpage': {
                    // options
                }
            },
            canvas: {
                styles: [
                    'https://cdn.tailwindcss.com', // Optional: load tailwind in canvas
                ]
            }
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
