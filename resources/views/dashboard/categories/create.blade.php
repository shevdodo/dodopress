<x-layouts.dashboard title="Create {{ ucfirst(request('type', '')) }} Category">
    <div class="mb-8">
        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('superuser.categories.index', ['type' => request('type')]) }}" class="hover:text-brand-600 transition">{{ ucfirst(request('type', '')) }} Categories</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Create Category</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Create New {{ ucfirst(request('type', '')) }} Category</h2>
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

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 max-w-3xl">
        <form action="{{ route('superuser.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-800 mb-1">Category Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition" required placeholder="e.g. Technology">
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-semibold text-gray-800 mb-1">Slug (Optional)</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition" placeholder="e.g. technology">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to auto-generate from name.</p>
                </div>

                <!-- Parent Category -->
                <div>
                    <label for="parent_id" class="block text-sm font-semibold text-gray-800 mb-1">Parent Category</label>
                    <select name="parent_id" id="parent_id" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition">
                        <option value="">None (Top Level)</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Type -->
                <div class="{{ request()->has('type') ? 'hidden' : '' }}">
                    <label for="type" class="block text-sm font-semibold text-gray-800 mb-1">Type</label>
                    <select name="type" id="type" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition">
                        <option value="post" {{ old('type', request('type')) == 'post' ? 'selected' : '' }}>Post</option>
                        <option value="product" {{ old('type', request('type')) == 'product' ? 'selected' : '' }}>Produk</option>
                    </select>
                </div>

                <!-- Image -->
                <div>
                    <x-media-picker name="image" label="Feature Image (Optional)" preview-size="lg" />
                </div>

                <h3 class="font-bold text-gray-900 mb-4 pb-3 border-b border-gray-100 mt-6">SEO Options</h3>
                
                <div>
                    <label for="meta_title" class="block text-sm font-semibold text-gray-800 mb-1">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition text-sm" placeholder="Custom SEO title">
                </div>

                <div>
                    <label for="meta_description" class="block text-sm font-semibold text-gray-800 mb-1">Meta Description</label>
                    <textarea name="meta_description" id="meta_description" rows="3" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition text-sm" placeholder="Brief description for search engines">{{ old('meta_description') }}</textarea>
                </div>

                <div>
                    <label for="meta_keywords" class="block text-sm font-semibold text-gray-800 mb-1">Meta Keywords</label>
                    <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords') }}" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition text-sm" placeholder="keyword1, keyword2, ...">
                </div>

                <div>
                    <label for="meta_schema" class="block text-sm font-semibold text-gray-800 mb-1">Custom Schema Markup (JSON-LD)</label>
                    <textarea name="meta_schema" id="meta_schema" rows="4" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition text-sm font-mono text-gray-700" placeholder='<script type="application/ld+json">...</script>'>{{ old('meta_schema') }}</textarea>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end space-x-3">
                <a href="{{ route('superuser.categories.index', ['type' => request('type')]) }}" class="px-5 py-2.5 text-gray-600 hover:text-gray-900 font-medium text-sm transition">Cancel</a>
                <button type="submit" class="px-5 py-2.5 bg-brand-600 text-white font-medium rounded-xl hover:bg-brand-700 shadow-lg shadow-brand-600/30 transition text-sm">Create Category</button>
            </div>
        </form>
    </div>
</x-layouts.dashboard>
