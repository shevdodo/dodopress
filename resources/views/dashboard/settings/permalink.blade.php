<x-layouts.dashboard title="Permalink Settings">
    <div class="mb-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Permalink Settings</h2>
        <p class="text-sm text-gray-500 mt-1">Configure custom URL structures for your posts and products.</p>
    </div>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-medium">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 max-w-3xl">
        <form action="{{ route('superuser.settings.permalink.update') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Post Permalink Base -->
                <div>
                    <label for="post_permalink_base" class="block text-sm font-semibold text-gray-800 mb-1">Post Permalink Base</label>
                    <p class="text-xs text-gray-500 mb-2">Set the base slug for blog posts. Leave empty to use the default root path (e.g. <code>/blog/my-post</code>).</p>
                    <div class="flex items-center w-full">
                        <span class="hidden sm:inline-block bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl px-4 py-2.5 text-gray-500 text-sm whitespace-nowrap">{{ url('/') }}/</span>
                        <span class="sm:hidden bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl px-4 py-2.5 text-gray-500 text-sm whitespace-nowrap">/</span>
                        <input type="text" name="post_permalink_base" id="post_permalink_base" value="{{ $settings['post_permalink_base'] ?? '' }}" class="flex-1 w-full min-w-0 border-gray-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition text-sm sm:rounded-none rounded-none" placeholder="e.g. blog or post">
                        <span class="bg-gray-100 border border-l-0 border-gray-300 rounded-r-xl px-4 py-2.5 text-gray-500 text-sm whitespace-nowrap">/{slug}</span>
                    </div>
                </div>

                <!-- Product Permalink Base -->
                <div>
                    <label for="product_permalink_base" class="block text-sm font-semibold text-gray-800 mb-1">Product Permalink Base</label>
                    <p class="text-xs text-gray-500 mb-2">Set the base slug for products. Leave empty to use the default root path (e.g. <code>/store/my-product</code>).</p>
                    <div class="flex items-center w-full">
                        <span class="hidden sm:inline-block bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl px-4 py-2.5 text-gray-500 text-sm whitespace-nowrap">{{ url('/') }}/</span>
                        <span class="sm:hidden bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl px-4 py-2.5 text-gray-500 text-sm whitespace-nowrap">/</span>
                        <input type="text" name="product_permalink_base" id="product_permalink_base" value="{{ $settings['product_permalink_base'] ?? '' }}" class="flex-1 w-full min-w-0 border-gray-300 focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition text-sm sm:rounded-none rounded-none" placeholder="e.g. store or product">
                        <span class="bg-gray-100 border border-l-0 border-gray-300 rounded-r-xl px-4 py-2.5 text-gray-500 text-sm whitespace-nowrap">/{slug}</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 text-right">
                <button type="submit" class="px-6 py-2.5 bg-brand-600 text-white font-medium rounded-xl hover:bg-brand-700 shadow-lg shadow-brand-600/30 transition text-sm">Save Permalinks</button>
            </div>
        </form>
    </div>
</x-layouts.dashboard>
