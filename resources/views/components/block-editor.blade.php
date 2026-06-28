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
@endphp

<textarea id="blocks-json" name="content" class="hidden">{{ json_encode(['blocks' => $existingData]) }}</textarea>

<div id="dd-block-editor" class="space-y-6">
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                Block Editor
            </h3>
            <span class="text-xs text-gray-400">Drag to reorder</span>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach($allBlocks as $type => $def)
            <button type="button" class="dd-add-block-btn inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 hover:bg-brand-50 border border-gray-200 hover:border-brand-200 rounded-lg text-sm text-gray-700 hover:text-brand-700 font-medium transition" data-block-type="{{ $type }}">
                <span class="w-4 h-4">{!! $def['icon'] ?? '' !!}</span>
                {{ $def['name'] ?? $type }}
            </button>
            @endforeach
        </div>
    </div>

    <div id="dd-blocks-container" class="space-y-3"></div>

    @if(empty($existingData))
    <div id="dd-empty-state" class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-gray-200">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        <p class="text-gray-500 font-medium">Belum ada blok</p>
        <p class="text-sm text-gray-400 mt-1">Klik tombol di atas untuk menambahkan blok</p>
    </div>
    @endif
</div>

<div id="dd-block-modal" class="hidden fixed inset-0 z-50 items-center justify-center p-4">
    <div class="dd-modal-backdrop fixed inset-0 bg-black/50"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h3 id="dd-block-modal-title" class="text-lg font-bold text-gray-900">Edit Block</h3>
            <button type="button" id="dd-modal-close" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="dd-block-form" class="p-6 overflow-y-auto flex-1 space-y-4"></div>
        <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
            <button type="button" id="dd-modal-cancel" class="px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 rounded-xl transition">Batal</button>
            <button type="button" id="dd-modal-save" class="px-5 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-xl shadow-lg shadow-brand-600/30 transition">Simpan</button>
        </div>
    </div>
</div>

<style>
#dd-blocks-container .dd-block-item{background:#fff;border:1px solid #e5e7eb;border-radius:1rem;overflow:hidden;transition:box-shadow .2s}
#dd-blocks-container .dd-block-item:hover{box-shadow:0 4px 12px rgba(0,0,0,.08)}
#dd-blocks-container .dd-block-header{display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;background:#f9fafb;border-bottom:1px solid #e5e7eb}
#dd-blocks-container .dd-block-handle{cursor:grab;color:#9ca3af;display:flex;align-items:center}
#dd-blocks-container .dd-block-handle:active{cursor:grabbing}
#dd-blocks-container .dd-block-icon{color:#6366f1;display:flex;align-items:center}
#dd-blocks-container .dd-block-name{font-weight:600;font-size:.875rem;color:#374151;flex:1}
#dd-blocks-container .dd-block-actions{display:flex;gap:.25rem}
#dd-blocks-container .dd-block-actions button{width:2rem;height:2rem;border-radius:.5rem;display:flex;align-items:center;justify-content:center;color:#6b7280;transition:all .15s}
#dd-blocks-container .dd-block-actions button:hover{background:#e5e7eb;color:#374151}
#dd-blocks-container .dd-block-actions .dd-btn-delete:hover{background:#fef2f2;color:#dc2626}
#dd-blocks-container .dd-block-preview{padding:.75rem 1rem;font-size:.8125rem;color:#6b7280;line-height:1.5}
.dd-block-ghost{opacity:.4}
.dd-block-dragging{background:#eef2ff}
.dd-preview-label{font-weight:600;color:#374151}
.dd-field{margin-bottom:1rem}
.dd-field-label{display:block;font-size:.875rem;font-weight:600;color:#374151;margin-bottom:.375rem}
.dd-field-input{width:100%;border:1px solid #d1d5db;border-radius:.75rem;padding:.625rem .875rem;font-size:.875rem;transition:border-color .15s}
.dd-field-input:focus{outline:none;border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.15)}
.dd-field-textarea{min-height:80px;resize:vertical}
.dd-image-field{display:flex;flex-direction:column;gap:.5rem}
.dd-image-preview{max-width:200px;max-height:120px;border-radius:.5rem;border:1px solid #e5e7eb;object-fit:cover}
.dd-btn-media{display:inline-flex;align-items:center;padding:.5rem 1rem;background:#6366f1;color:#fff;font-size:.8125rem;font-weight:600;border-radius:.5rem;border:none;cursor:pointer;transition:background .15s}
.dd-btn-media:hover{background:#4f46e5}
.dd-btn-sm{padding:.375rem .75rem;font-size:.75rem}
.dd-gallery-field{display:flex;flex-direction:column;gap:.5rem}
.dd-gallery-list{display:flex;flex-wrap:wrap;gap:.5rem}
.dd-gallery-item{position:relative;width:80px;height:80px;border-radius:.5rem;overflow:hidden;border:1px solid #e5e7eb}
.dd-gallery-item img{width:100%;height:100%;object-fit:cover}
.dd-gallery-remove{position:absolute;top:2px;right:2px;width:20px;height:20px;border-radius:50%;background:rgba(0,0,0,.5);color:#fff;font-size:14px;line-height:20px;text-align:center;border:none;cursor:pointer}
.dd-repeater-field{display:flex;flex-direction:column;gap:.5rem}
.dd-repeater-list{display:flex;flex-direction:column;gap:.5rem}
.dd-repeater-item{display:flex;gap:.5rem;align-items:center;padding:.5rem;background:#f9fafb;border-radius:.5rem;border:1px solid #e5e7eb}
.dd-repeater-input{flex:1;min-width:80px}
.dd-repeater-remove{width:28px;height:28px;border-radius:50%;background:#fee2e2;color:#dc2626;font-size:16px;border:none;cursor:pointer;flex-shrink:0}
</style>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>window.DD_BLOCKS = @json($allBlocks);</script>
<script src="{{ asset('js/block-editor.js') }}"></script>

