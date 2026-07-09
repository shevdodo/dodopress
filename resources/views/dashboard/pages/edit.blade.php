<x-layouts.dashboard title="Edit Page">
    <div class="mb-8">
        <a href="{{ route('superuser.pages.index') }}" class="text-sm text-brand-600 hover:text-brand-800 flex items-center space-x-1 mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            <span>Back to Pages</span>
        </a>
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Page</h2>
        <p class="text-sm text-gray-500 mt-1">Update your content and visibility.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm font-medium">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-medium flex items-center space-x-3">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('superuser.pages.update', $page) }}" enctype="multipart/form-data" class="flex flex-col lg:flex-row gap-8">
        @csrf
        @method('PUT')

        @if($page->template === 'block')
        {{-- ===== BLOCK EDITOR ===== --}}
        <input type="hidden" name="template" value="block">
        <div class="flex-1 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <div class="mb-6">
                    <label for="title" class="block text-sm font-semibold text-gray-800 mb-1">Page Title <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2">
                </div>
                <div class="mb-6">
                    <label for="slug" class="block text-sm font-semibold text-gray-800 mb-1">Slug</label>
                    <p class="text-xs text-gray-500 mb-2">Leave blank to auto-generate from title.</p>
                    <div class="flex items-center">
                        <span class="bg-gray-50 border border-r-0 border-gray-300 rounded-l-lg px-3 py-2 text-gray-500 text-sm">{{ url('/') }}/</span>
                        <input type="text" id="slug" name="slug" value="{{ old('slug', $page->slug) }}" class="flex-1 border-gray-300 rounded-r-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2">
                    </div>
                </div>
            </div>
            <x-block-editor :content="$page->content ?? ''" />
        </div>
        @elseif($page->template === 'ux-builder')
        {{-- ===== UX BUILDER EDITOR ===== --}}
        <div class="flex-1 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <div class="mb-6">
                    <label for="title" class="block text-sm font-semibold text-gray-800 mb-1">Page Title <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2">
                </div>

                <div class="mb-6">
                    <label for="slug" class="block text-sm font-semibold text-gray-800 mb-1">Slug</label>
                    <div class="flex items-center">
                        <span class="bg-gray-50 border border-r-0 border-gray-300 rounded-l-lg px-3 py-2 text-gray-500 text-sm">{{ url('/') }}/</span>
                        <input type="text" id="slug" name="slug" value="{{ old('slug', $page->slug) }}" class="flex-1 border-gray-300 rounded-r-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2">
                    </div>
                </div>

                <div class="p-8 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" /></svg>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Visual Page Builder</h3>
                    <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">This page is using the GrapesJS UX Builder. Click the button below to edit the page visually.</p>
                    <a href="{{ route('superuser.pages.builder', $page->id) }}" class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg transition-transform transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        Open UX Builder
                    </a>
                </div>
            </div>
        </div>
        @else
        {{-- ===== REGULAR PAGE EDITOR ===== --}}
        <div class="flex-1 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <div class="mb-6">
                    <label for="title" class="block text-sm font-semibold text-gray-800 mb-1">Page Title <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2">
                </div>

                <div class="mb-6">
                    <label for="slug" class="block text-sm font-semibold text-gray-800 mb-1">Slug</label>
                    <p class="text-xs text-gray-500 mb-2">Leave blank to auto-generate from title.</p>
                    <div class="flex items-center">
                        <span class="bg-gray-50 border border-r-0 border-gray-300 rounded-l-lg px-3 py-2 text-gray-500 text-sm">{{ url('/') }}/</span>
                        <input type="text" id="slug" name="slug" value="{{ old('slug', $page->slug) }}" class="flex-1 border-gray-300 rounded-r-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2">
                    </div>
                </div>

                <div>
                    <label for="content" class="block text-sm font-semibold text-gray-800 mb-1">Page Content</label>
                    <textarea id="content" name="content" rows="12" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2">{{ old('content', $page->content) }}</textarea>
                </div>
            </div>
        </div>
        @endif

        <div class="w-full lg:w-80 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <h3 class="font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Page Attributes</h3>
                
                <div class="mb-4">
                    <label for="parent_id" class="block text-sm font-semibold text-gray-800 mb-1">Parent Page</label>
                    <select id="parent_id" name="parent_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 bg-white">
                        <option value="">(no parent)</option>
                        @foreach($parentPages as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $page->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="template" class="block text-sm font-semibold text-gray-800 mb-1">Template</label>
                    <select id="template" name="template" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 bg-white">
                        <option value="default" {{ old('template', $page->template) == 'default' ? 'selected' : '' }}>Default Template</option>
                        <option value="full-width" {{ old('template', $page->template) == 'full-width' ? 'selected' : '' }}>Full Width</option>
                        <option value="blank" {{ old('template', $page->template) == 'blank' ? 'selected' : '' }}>Blank (Raw HTML)</option>

                        <option value="ux-builder" {{ old('template', $page->template) == 'ux-builder' ? 'selected' : '' }}>UX Builder (GrapesJS)</option>
                        <option value="block" {{ old('template', $page->template) == 'block' ? 'selected' : '' }}>Block Editor</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label for="order" class="block text-sm font-semibold text-gray-800 mb-1">Order</label>
                    <input type="number" id="order" name="order" value="{{ old('order', $page->order) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2">
                </div>

                <h3 class="font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100 mt-6">SEO Options</h3>
                
                <div class="mb-4">
                    <label for="meta_title" class="block text-sm font-semibold text-gray-800 mb-1">Meta Title</label>
                    <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 text-sm" placeholder="Custom SEO title">
                </div>

                <div class="mb-4">
                    <label for="meta_description" class="block text-sm font-semibold text-gray-800 mb-1">Meta Description</label>
                    <textarea id="meta_description" name="meta_description" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 text-sm" placeholder="Brief description for search engines">{{ old('meta_description', $page->meta_description) }}</textarea>
                </div>

                <div class="mb-6">
                    <label for="meta_keywords" class="block text-sm font-semibold text-gray-800 mb-1">Meta Keywords</label>
                    <input type="text" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $page->meta_keywords) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 text-sm" placeholder="keyword1, keyword2, ...">
                </div>

                <div class="mb-6">
                    <label for="meta_schema" class="block text-sm font-semibold text-gray-800 mb-1">Custom Schema Markup (JSON-LD)</label>
                    <textarea id="meta_schema" name="meta_schema" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 text-sm font-mono text-gray-700" placeholder='<script type="application/ld+json">...</script>'>{{ old('meta_schema', $page->meta_schema) }}</textarea>
                </div>

                <h3 class="font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100 mt-6">Publishing</h3>
                
                <div class="mb-4">
                    <label for="status" class="block text-sm font-semibold text-gray-800 mb-1">Status</label>
                    <select id="status" name="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 bg-white">
                        <option value="draft" {{ old('status', $page->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $page->status) == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>

                <button type="submit" class="w-full px-6 py-2.5 bg-brand-600 text-white font-semibold rounded-xl hover:bg-brand-700 transition shadow-lg shadow-brand-600/30">
                    Save Changes
                </button>
            </div>
        </div>
    </form>

    @if($page->template !== 'block')
    <style>
        /* Hide TinyMCE API Key Warning */
        .tox-notifications-container { display: none !important; }
    </style>
    <!-- TinyMCE Classic Editor Setup -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            tinymce.init({
                selector: '#content',
                height: 600,
                menubar: true,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | blocks | ' +
                'bold italic forecolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'link image media table | removeformat | fullscreen code help',
                content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-size: 16px; color: #374151; }',
                extended_valid_elements: "svg[*],use[*],path[*]",
                custom_elements: "svg,path,use",
                promotion: false,
                branding: false
            });
        });
    </script>
    @endif


</x-layouts.dashboard>
