<x-layouts.dashboard title="AI Visibility & AIO">
    <div class="mb-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
            AI Visibility & AIO
        </h2>
        <p class="text-sm text-gray-500 mt-1">Optimize your website to be correctly read and indexed by AI Search Engines (like Perplexity, ChatGPT, and Claude).</p>
    </div>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-medium flex items-center space-x-3">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- AI Score Card -->
        <div class="bg-gradient-to-br from-indigo-900 to-indigo-700 rounded-2xl shadow-lg p-6 text-white flex flex-col justify-center items-center text-center">
            <h3 class="font-bold text-indigo-100 mb-2">AI Readiness Score</h3>
            <div class="relative w-32 h-32 flex items-center justify-center">
                <!-- Circular Progress -->
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                    <path class="text-indigo-950/30" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3.8"/>
                    <path class="text-emerald-400 drop-shadow-md" stroke-dasharray="{{ $score }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3.8" stroke-linecap="round"/>
                </svg>
                <div class="absolute flex flex-col items-center">
                    <span class="text-3xl font-black">{{ $score }}<span class="text-lg text-indigo-200">%</span></span>
                </div>
            </div>
            <p class="mt-4 text-xs text-indigo-200">Based on Sitemap, Meta, Schema and Robots.txt configuration.</p>
        </div>

        <!-- Checklist -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-center">
            <h3 class="font-bold text-gray-800 mb-4 text-lg">AIO Checklist</h3>
            <ul class="space-y-4">
                <li class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-sm text-gray-700 font-medium">Schema.org JSON-LD Implemented</span>
                </li>
                <li class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-sm text-gray-700 font-medium">OpenGraph Metadata Ready</span>
                </li>
                <li class="flex items-center space-x-3">
                    @if($score >= 75)
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    @else
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    @endif
                    <span class="text-sm text-gray-700 font-medium">XML Sitemap Index Linked in robots.txt</span>
                </li>
                <li class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-sm text-gray-700 font-medium">Semantic HTML tags (header, nav, article) Active</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Bot Control Form -->
    <form method="POST" action="{{ route('superuser.settings.ai-visibility.update') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @csrf
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-800">AI Crawler Controls</h3>
            <p class="text-xs text-gray-500 mt-1">Control which AI companies are allowed to scrape your website for training or answering user queries.</p>
        </div>
        
        <div class="divide-y divide-gray-100">
            @foreach(['GPTBot' => 'OpenAI (ChatGPT)', 'ClaudeBot' => 'Anthropic (Claude)', 'PerplexityBot' => 'Perplexity AI', 'Applebot-Extended' => 'Apple AI'] as $botKey => $botName)
                <div class="px-6 py-5 flex items-center justify-between hover:bg-gray-50 transition-colors">
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">{{ $botName }}</h4>
                        <p class="text-xs text-gray-500 mt-0.5">User-agent: <code class="bg-gray-100 text-indigo-600 px-1 rounded">{{ $botKey }}</code></p>
                    </div>
                    
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="{{ $botKey }}" value="0">
                        <input type="checkbox" name="{{ $botKey }}" value="1" class="sr-only peer" {{ $bots[$botKey] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        <span class="ml-3 text-sm font-medium {{ $bots[$botKey] ? 'text-emerald-600' : 'text-gray-500' }}">
                            {{ $bots[$botKey] ? 'Allowed' : 'Blocked' }}
                        </span>
                    </label>
                </div>
            @endforeach
        </div>

        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 flex justify-end">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold transition-all shadow-lg shadow-indigo-600/30 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save AI Preferences</span>
            </button>
        </div>
    </form>
</x-layouts.dashboard>
