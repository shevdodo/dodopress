<x-layouts.dashboard title="SEO & Sitemap Settings">
    <div class="mb-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">SEO & Sitemap Settings</h2>
        <p class="text-sm text-gray-500 mt-1">Configure search engine visibility and auto-generated XML sitemaps.</p>
    </div>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-medium flex items-center space-x-3">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('superuser.settings.seo.update') }}" class="max-w-4xl space-y-6">
        @csrf

        {{-- Sitemap Settings --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">XML Sitemap Generator</h3>
                <a href="{{ url('/sitemap.xml') }}" target="_blank" class="text-sm text-brand-600 hover:text-brand-700 font-semibold flex items-center gap-1">
                    View Sitemap 
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                </a>
            </div>
            
            <div class="p-6 space-y-6">
                @php
                    $sitemapEnabled = \App\Models\Setting::where('key', 'sitemap_enabled')->value('value') ?? '1';
                @endphp
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Enable Auto-Generated Sitemap</label>
                    <p class="text-xs text-gray-500 mb-3">If enabled, a dynamic XML sitemap will be generated automatically at <code class="bg-gray-100 px-1 py-0.5 rounded text-brand-600">{{ url('/sitemap.xml') }}</code>.</p>
                    
                    <div class="flex items-center space-x-6">
                        <label class="flex items-center space-x-2 cursor-pointer group">
                            <input type="radio" name="sitemap_enabled" value="1" {{ $sitemapEnabled == '1' ? 'checked' : '' }} class="w-4 h-4 text-brand-600 focus:ring-brand-500 border-gray-300">
                            <span class="text-sm text-gray-700 group-hover:text-gray-900 font-medium">Enabled (Recommended)</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer group">
                            <input type="radio" name="sitemap_enabled" value="0" {{ $sitemapEnabled == '0' ? 'checked' : '' }} class="w-4 h-4 text-brand-600 focus:ring-brand-500 border-gray-300">
                            <span class="text-sm text-gray-700 group-hover:text-gray-900 font-medium">Disabled</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Robots.txt --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Robots.txt Editor</h3>
                <a href="{{ url('/robots.txt') }}" target="_blank" class="text-sm text-gray-500 hover:text-gray-700 font-medium flex items-center gap-1">
                    View robots.txt
                </a>
            </div>
            
            <div class="p-6">
                @php
                    $robotsContent = '';
                    if (file_exists(public_path('robots.txt'))) {
                        $robotsContent = file_get_contents(public_path('robots.txt'));
                    }
                @endphp
                <p class="text-xs text-gray-500 mb-4">Edit your robots.txt rules directly below. Be careful, incorrect rules can block search engines from indexing your site.</p>
                <textarea name="robots_txt" rows="8" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-3 font-mono text-sm text-gray-700 bg-gray-50">{{ $robotsContent }}</textarea>
                
                <div class="mt-4 p-4 bg-blue-50 border border-blue-100 rounded-xl text-sm text-blue-800">
                    <strong>Tip:</strong> Don't forget to include your sitemap in your robots.txt file by adding this line at the bottom:
                    <div class="mt-1 font-mono text-xs bg-white/50 p-2 rounded border border-blue-200">Sitemap: {{ url('/sitemap.xml') }}</div>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white px-8 py-2.5 rounded-xl font-bold transition-all shadow-lg shadow-brand-600/30 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save SEO Settings</span>
            </button>
        </div>
    </form>
</x-layouts.dashboard>
