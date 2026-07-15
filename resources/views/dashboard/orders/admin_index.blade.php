<x-layouts.dashboard title="List Orders">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">List Orders</h2>
            <p class="text-sm text-gray-500 mt-1">Manage and view all orders placed by users.</p>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 text-green-700 text-sm font-medium flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50 border-b border-gray-100 text-gray-500">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-gray-600">Order ID</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Customer</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Date</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Amount</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Status</th>
                        <th class="px-6 py-4 font-semibold text-gray-600 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 font-bold text-gray-900">{{ $order->order_number }}</td>
                            <td class="px-6 py-4">
                                @if($order->user)
                                    <div class="font-medium text-gray-900">{{ $order->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->user->email }}</div>
                                @else
                                    <span class="text-gray-400 italic">Guest</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-4 font-bold text-brand-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold uppercase tracking-wider">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('superuser.orders.show', $order) }}" class="inline-flex items-center space-x-1 text-brand-600 hover:text-brand-800 font-medium text-sm bg-brand-50 hover:bg-brand-100 px-3 py-1.5 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <span>Details</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="w-12 h-12 bg-gray-50 rounded-full border border-gray-100 flex items-center justify-center mx-auto mb-3 text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                                </div>
                                <h3 class="text-base font-bold text-gray-900 mb-1">No Orders Found</h3>
                                <p class="text-sm text-gray-500">There are no orders yet in the system.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-layouts.dashboard>
