<x-layouts.dashboard title="Contact Messages">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Contact Messages</h2>
            <p class="text-sm text-gray-500 mt-1">
                View and manage messages sent from the Contact Us page.
            </p>
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
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500">
                        <th class="px-6 py-4 font-semibold">Sender</th>
                        <th class="px-6 py-4 font-semibold">Subject</th>
                        <th class="px-6 py-4 font-semibold">Date</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($messages as $message)
                        <tr class="hover:bg-gray-50/50 transition {{ $message->is_read ? 'bg-white' : 'bg-brand-50/30 font-semibold' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <p class="text-gray-900">{{ $message->name }}</p>
                                    <p class="text-gray-500 text-xs">{{ $message->email }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-gray-800">{{ $message->subject ?? '(No Subject)' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                {{ $message->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('superuser.contact-messages.show', $message) }}" class="text-brand-600 hover:text-brand-900 mr-3 inline-block">View</a>
                                <form action="{{ route('superuser.contact-messages.destroy', $message) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
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
