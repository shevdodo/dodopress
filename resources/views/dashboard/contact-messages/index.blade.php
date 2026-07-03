<x-layouts.dashboard title="Contact Messages">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 space-y-4 sm:space-y-0">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Contact Messages</h2>
        </div>
        <div class="flex items-center space-x-4">
            <div class="relative">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" placeholder="Search messages..." class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/50 bg-gray-50/50 min-w-[200px]">
            </div>
            <div class="text-sm text-gray-500 font-medium">
                {{ $messages->total() }} total
            </div>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-medium flex items-center space-x-3">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100 text-xs font-bold uppercase tracking-wider text-gray-500">
                        <th class="px-6 py-5 w-10">
                            <input type="checkbox" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                        </th>
                        <th class="px-4 py-5 font-bold">Sender</th>
                        <th class="px-6 py-5 font-bold hidden md:table-cell">Email</th>
                        <th class="px-6 py-5 font-bold hidden sm:table-cell">Subject</th>
                        <th class="px-6 py-5 font-bold hidden sm:table-cell">Date</th>
                        <th class="px-6 py-5 font-bold text-right hidden sm:table-cell">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($messages as $message)
                        <tr class="hover:bg-gray-50/50 transition {{ $message->is_read ? 'bg-white' : 'bg-brand-50/30 font-semibold' }}">
                            <td class="px-6 py-4">
                                <input type="checkbox" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 rounded-full {{ $message->is_read ? 'bg-gray-200 text-gray-600' : 'bg-[#c87652] text-white' }} flex items-center justify-center font-bold uppercase shrink-0 hidden sm:flex">
                                        {{ substr($message->name, 0, 2) }}
                                    </div>
                                    <div class="w-full">
                                        <div class="flex justify-between items-start sm:block">
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $message->name }}</p>
                                                <p class="text-gray-500 text-sm truncate max-w-[150px] md:hidden">{{ $message->email }}</p>
                                            </div>
                                            <div class="sm:hidden text-right">
                                                <span class="text-[10px] text-gray-400">{{ $message->created_at->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                        
                                        <p class="text-sm mt-2 text-gray-700 sm:hidden">
                                            <span class="font-semibold text-xs text-gray-400 uppercase tracking-wider block mb-0.5">Subject</span>
                                            {{ $message->subject ?? '(No Subject)' }}
                                        </p>

                                        <!-- Mobile Actions -->
                                        <div class="mt-3 sm:hidden flex items-center space-x-4 text-xs font-medium pt-2 border-t border-gray-100">
                                            <a href="{{ route('superuser.contact-messages.show', $message) }}" class="text-gray-400 hover:text-indigo-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            </a>
                                            <form action="{{ route('superuser.contact-messages.destroy', $message) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-600">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 text-sm hidden md:table-cell">
                                {{ $message->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-800 text-sm hidden sm:table-cell">
                                {{ Str::limit($message->subject ?? '(No Subject)', 30) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 text-sm hidden sm:table-cell">
                                {{ $message->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium hidden sm:table-cell">
                                <div class="flex items-center justify-end space-x-3">
                                    <a href="{{ route('superuser.contact-messages.show', $message) }}" class="text-gray-400 hover:text-indigo-600 transition" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                    <form action="{{ route('superuser.contact-messages.destroy', $message) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                No messages found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($messages->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
</x-layouts.dashboard>
