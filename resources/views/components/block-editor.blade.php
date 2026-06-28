@props(['content' => ''])
@php
    $allBlocks = config('blocks.blocks', []);
    $existingData = [];
    if (!empty($content)) {
        $decoded = json_decode($content, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $existingData = $decoded['blocks'] ?? $decoded;
        }
    }
    $allProductCategories = \App\Models\Category::where('type', 'product')->orderBy('name')->get(['id', 'name']);
@endphp

<textarea id="blocks-json" name="content" class="hidden">{{ json_encode(['blocks' => $existingData]) }}</textarea>
<script>
    window.appProductCategories = @json($allProductCategories);
</script>


<div id="dd-block-editor" class="space-y-4">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                    <svg class="w-4.5 h-4.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                </div>
                <h3 class="font-semibold text-gray-900">Block Editor</h3>
                <span class="hidden sm:inline text-xs text-gray-400">— Drag to reorder</span>
            </div>
            <button type="button" id="dd-collapse-btn" class="text-xs text-gray-400 hover:text-gray-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
        </div>
        <div class="px-5 py-4">
            <p class="text-xs text-gray-400 mb-3">Add a block</p>
            <div class="flex flex-wrap gap-2">
                @foreach($allBlocks as $type => $def)
                <button type="button" class="dd-add-block-btn group inline-flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 rounded-lg text-sm text-gray-600 hover:text-indigo-700 font-medium transition-all duration-150 shadow-sm hover:shadow" data-block-type="{{ $type }}">
                    <span class="w-4 h-4 text-gray-400 group-hover:text-indigo-500 transition">@php echo $def['icon'] ?? '' @endphp</span>
                    <span>{{ $def['name'] ?? $type }}</span>
                </button>
                @endforeach
            </div>
        </div>
    </div>

    <div id="dd-blocks-container" class="space-y-3"></div>

    @if(empty($existingData))
    <div id="dd-empty-state" class="text-center py-16 bg-white rounded-xl border-2 border-dashed border-gray-200">
        <div class="w-14 h-14 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-4">
            <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        </div>
        <p class="text-gray-500 font-medium">No blocks yet</p>
        <p class="text-sm text-gray-400 mt-1">Click any button above to start building your page</p>
    </div>
    @endif
</div>

<div id="dd-block-modal" class="hidden fixed inset-0 z-[100] items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-xl max-h-[85vh] flex flex-col border border-gray-200 animate-modal-in">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h3 id="dd-block-modal-title" class="font-semibold text-gray-900 text-sm">Edit Block</h3>
            </div>
            <button type="button" id="dd-modal-close" class="w-7 h-7 rounded-lg hover:bg-gray-100 flex items-center justify-center transition">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="dd-block-form" class="p-6 overflow-y-auto flex-1 space-y-5"></div>
        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-xl">
            <button type="button" id="dd-modal-cancel" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition">Cancel</button>
            <button type="button" id="dd-modal-save" class="px-5 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition">Save Changes</button>
        </div>
    </div>
</div>

<style>
.animate-modal-in { animation: modalIn 0.2s ease-out; }
@keyframes modalIn { from { opacity: 0; transform: scale(0.95) translateY(8px); } to { opacity: 1; transform: scale(1) translateY(0); } }
#dd-blocks-container { display: block !important; }
#dd-blocks-container > .dd-block-item { display: block !important; width: 100% !important; background: #fff; border: 1px solid #e5e7eb; border-radius: 0.75rem; overflow: hidden; transition: box-shadow 0.2s, border-color 0.2s; }
#dd-blocks-container .dd-block-item:hover { border-color: #c7d2fe; box-shadow: 0 4px 12px rgba(99,102,241,0.08); }
#dd-blocks-container .dd-block-header { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; background: #f8fafc; border-bottom: 1px solid #f1f5f9; }
#dd-blocks-container .dd-block-handle { cursor: grab; color: #94a3b8; display: flex; align-items: center; padding: 2px; border-radius: 4px; transition: color 0.15s; }
#dd-blocks-container .dd-block-handle:hover { color: #6366f1; }
#dd-blocks-container .dd-block-handle:active { cursor: grabbing; }
#dd-blocks-container .dd-block-icon { color: #6366f1; display: flex; align-items: center; width: 1.25rem; height: 1.25rem; }
#dd-blocks-container .dd-block-icon svg { width: 100%; height: 100%; }
#dd-blocks-container .dd-block-name { font-weight: 600; font-size: 0.8125rem; color: #1e293b; flex: 1; }
#dd-blocks-container .dd-block-actions { display: flex; gap: 0.125rem; }
#dd-blocks-container .dd-block-actions button { width: 1.75rem; height: 1.75rem; border-radius: 0.375rem; display: flex; align-items: center; justify-content: center; color: #64748b; transition: all 0.15s; }
#dd-blocks-container .dd-block-actions button:hover { background: #f1f5f9; color: #334155; }
#dd-blocks-container .dd-block-actions .dd-btn-delete:hover { background: #fef2f2; color: #dc2626; }
#dd-blocks-container .dd-block-actions .dd-btn-clone:hover { background: #f0f9ff; color: #2563eb; }
#dd-blocks-container .dd-block-preview { padding: 0.75rem 1rem; font-size: 0.8125rem; color: #64748b; line-height: 1.5; background: #fff; }
.dd-block-ghost { opacity: 0.4; }
.dd-block-dragging { background: #eef2ff !important; }
.dd-preview-label { font-weight: 600; color: #334155; }
.dd-field { margin-bottom: 0; }
.dd-field-label { display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem; }
.dd-field-input { width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; transition: border-color 0.15s, box-shadow 0.15s; background: #fff; }
.dd-field-input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
.dd-field-textarea { min-height: 80px; resize: vertical; }
.dd-image-field { display: flex; flex-direction: column; gap: 0.5rem; }
.dd-image-preview { max-width: 200px; max-height: 120px; border-radius: 0.5rem; border: 1px solid #e5e7eb; object-fit: cover; }
.dd-btn-media { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.5rem 1rem; background: #6366f1; color: #fff; font-size: 0.8125rem; font-weight: 600; border-radius: 0.5rem; border: none; cursor: pointer; transition: background 0.15s; }
.dd-btn-media:hover { background: #4f46e5; }
.dd-btn-sm { padding: 0.375rem 0.75rem; font-size: 0.75rem; }
.dd-gallery-field { display: flex; flex-direction: column; gap: 0.5rem; }
.dd-gallery-list { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.dd-gallery-item { position: relative; width: 80px; height: 80px; border-radius: 0.5rem; overflow: hidden; border: 1px solid #e5e7eb; }
.dd-gallery-item img { width: 100%; height: 100%; object-fit: cover; }
.dd-gallery-remove { position: absolute; top: 2px; right: 2px; width: 20px; height: 20px; border-radius: 50%; background: rgba(0,0,0,0.5); color: #fff; font-size: 14px; line-height: 20px; text-align: center; border: none; cursor: pointer; transition: background 0.15s; }
.dd-gallery-remove:hover { background: rgba(220,38,38,0.8); }
.dd-repeater-field { display: flex; flex-direction: column; gap: 0.5rem; }
.dd-repeater-list { display: flex; flex-direction: column; gap: 0.5rem; }
.dd-repeater-item { display: flex; gap: 0.5rem; align-items: center; padding: 0.5rem; background: #f8fafc; border-radius: 0.5rem; border: 1px solid #e2e8f0; }
.dd-repeater-input { flex: 1; min-width: 80px; }
.dd-repeater-remove { width: 28px; height: 28px; border-radius: 50%; background: #fee2e2; color: #dc2626; font-size: 16px; border: none; cursor: pointer; flex-shrink: 0; transition: background 0.15s; }
.dd-repeater-remove:hover { background: #fecaca; }
</style>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    window.DD_BLOCKS = @json($allBlocks);
    window.DD_STORAGE_URL = '{{ asset("storage") }}/';
</script><script src="{{ asset('js/block-editor.js') }}?v={{ filemtime(public_path('js/block-editor.js')) }}"></script>

{{-- Global Media Library Modal for Block Editor --}}
<div id="dd-global-media-modal"
    x-data="globalMediaModal()"
    x-show="open"
    @open-global-media.window="openModal($event.detail.callback)"
    @keydown.escape.window="open = false"
    class="fixed inset-0 z-[200] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm"
    style="display:none">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-4 max-h-[85vh] flex flex-col overflow-hidden" @click.stop>
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
            <h3 class="font-bold text-gray-900 text-lg">Pilih dari Media</h3>
            <div class="flex items-center gap-3">
                <input x-model="search" @input.debounce.400ms="loadMedia()"
                    type="text" placeholder="Cari gambar..."
                    class="text-sm border-gray-300 rounded-lg px-3 py-1.5 focus:border-brand-500 focus:ring focus:ring-brand-500/20">
                <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto p-6 relative">
            <template x-if="loading">
                <div class="flex items-center justify-center h-48">
                    <svg class="animate-spin w-8 h-8 text-brand-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                </div>
            </template>
            <template x-if="!loading && files.length === 0">
                <div class="text-center py-16 text-gray-400">
                    <p class="font-medium">Belum ada gambar di media library</p>
                </div>
            </template>
            <div x-show="!loading && files.length > 0" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3">
                <template x-for="file in files" :key="file.path">
                    <button type="button" @click="selectFile(file)"
                        :class="selected && selected.path === file.path ? 'ring-2 ring-brand-500 ring-offset-2' : 'hover:ring-2 hover:ring-gray-300 hover:ring-offset-1'"
                        class="relative aspect-square rounded-lg overflow-hidden bg-gray-100 transition group">
                        <img :src="file.url" :alt="file.name" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition"></div>
                        <template x-if="selected && selected.path === file.path">
                            <div class="absolute top-1 right-1 bg-brand-500 rounded-full w-5 h-5 flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                        </template>
                    </button>
                </template>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-gray-50">
            <p x-show="selected" class="text-sm text-gray-600 truncate max-w-xs" x-text="selected ? selected.name : ''"></p>
            <div class="flex gap-3 ml-auto">
                <button type="button" @click="open = false" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 font-medium transition">Batal</button>
                <button type="button" @click="confirmSelect()" :disabled="!selected" :class="selected ? 'bg-brand-600 hover:bg-brand-700 text-white' : 'bg-gray-200 text-gray-400 cursor-not-allowed'" class="px-5 py-2 text-sm font-semibold rounded-xl transition shadow-sm">Pilih Gambar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function globalMediaModal() {
        return {
            open: false,
            loading: false,
            files: [],
            selected: null,
            search: '',
            callback: null,

            openModal(cb) {
                this.callback = cb;
                this.open = true;
                this.search = '';
                this.$nextTick(() => {
                    this.loadMedia();
                });
            },
            loadMedia() {
                this.loading = true;
                this.selected = null;
                const querySearch = this.search || '';
                fetch(`{{ route('superuser.media.api') }}?type=image&search=${encodeURIComponent(querySearch)}&_t=${new Date().getTime()}`)
                    .then(r => r.json())
                    .then(data => {
                        this.files = data.files || [];
                        this.loading = false;
                    })
                    .catch(() => { this.loading = false; });
            },
            selectFile(file) {
                this.selected = file;
            },
            confirmSelect() {
                if (!this.selected || !this.callback) return;
                // Just pass the path so the frontend asset helper can render it properly, 
                // or the URL if it works directly. Dodopress block frontend might expect relative path for asset('storage/..')
                // Let's pass the relative path.
                this.callback(this.selected.path, this.selected.url);
                this.open = false;
            }
        }
    }
</script>
