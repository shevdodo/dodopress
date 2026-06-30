<x-layouts.dashboard title="View Contact Message">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Message Details</h2>
            <p class="text-sm text-gray-500 mt-1">
                View message sent from the Contact Us page.
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('superuser.contact-messages.index') }}" class="bg-white border border-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-medium shadow-sm hover:bg-gray-50 transition flex items-center space-x-2 inline-flex">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Back to Messages</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6 sm:p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Sender Information</h3>
                <div class="space-y-4">
                    <div>
                        <span class="block text-xs text-gray-400 mb-1">Name</span>
                        <p class="font-medium text-gray-900">{{ $contactMessage->name }}</p>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-400 mb-1">Email</span>
                        <p class="font-medium text-gray-900">
                            <a href="mailto:{{ $contactMessage->email }}" class="text-brand-600 hover:underline">{{ $contactMessage->email }}</a>
                        </p>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-400 mb-1">Phone</span>
                        <p class="font-medium text-gray-900">{{ $contactMessage->phone ?: '-' }}</p>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Message Details</h3>
                <div class="space-y-4">
                    <div>
                        <span class="block text-xs text-gray-400 mb-1">Subject</span>
                        <p class="font-medium text-gray-900">{{ $contactMessage->subject ?: '-' }}</p>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-400 mb-1">Received At</span>
                        <p class="font-medium text-gray-900">{{ $contactMessage->created_at->format('l, F j, Y \a\t H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-8">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Message Content</h3>
            <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">{{ $contactMessage->message }}</p>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
