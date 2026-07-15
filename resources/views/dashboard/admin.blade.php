<x-layouts.dashboard title="Admin Dashboard">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Store Management Console</h2>
            <p class="text-sm text-gray-500 mt-1">
                Manage your e-commerce operations, orders, and products.
            </p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('superuser.products.index') }}" class="px-5 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-xl hover:bg-brand-700 transition shadow-lg shadow-brand-600/25 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Product</span>
            </a>
            <a href="{{ route('superuser.orders.index') }}" class="px-5 py-2.5 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition flex items-center justify-center">
                View All Orders
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 p-5 card-hover shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Orders</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $stats['total_orders'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center shadow-lg shadow-sky-200 shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 card-hover shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Pending Orders</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $stats['pending_orders'] }}</p>
                    @if($stats['pending_orders'] > 0)
                        <p class="text-xs text-amber-600 font-medium mt-1">Needs attention</p>
                    @else
                        <p class="text-xs text-gray-400 font-medium mt-1">All caught up!</p>
                    @endif
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-200 shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 card-hover shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Products</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $stats['total_products'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-200 shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 card-hover shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Unread Messages</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $stats['unread_messages'] }}</p>
                    @if($stats['unread_messages'] > 0)
                        <p class="text-xs text-rose-600 font-medium mt-1">Please reply soon</p>
                    @else
                        <p class="text-xs text-gray-400 font-medium mt-1">Inbox zero!</p>
                    @endif
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-200 shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Content Area --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-8">
        {{-- Recent Orders Table --}}
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <h3 class="text-lg font-bold text-gray-900">Recent Orders</h3>
                    <a href="{{ route('superuser.orders.index') }}" class="text-sm text-brand-600 hover:text-brand-700 font-medium flex items-center">
                        View All Orders
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-3.5">Order</th>
                                <th class="px-6 py-3.5">Customer</th>
                                <th class="px-6 py-3.5 hidden sm:table-cell">Status</th>
                                <th class="px-6 py-3.5 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($recent_orders as $order)
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">
                                        <a href="{{ route('superuser.orders.show', $order->id) }}" class="hover:text-brand-600">{{ $order->order_number }}</a>
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }}</div>
                                    <div class="sm:hidden mt-2">
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider 
                                            {{ $order->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 
                                               ($order->status === 'pending' ? 'bg-amber-100 text-amber-700' : 
                                               ($order->status === 'cancelled' ? 'bg-rose-100 text-rose-700' : 'bg-blue-100 text-blue-700')) }}">
                                            {{ $order->status }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($order->user)
                                        <div class="font-medium text-gray-800">{{ $order->user->name }}</div>
                                        <div class="text-xs text-gray-500 hidden sm:block">{{ $order->user->email }}</div>
                                    @else
                                        <span class="text-gray-500 italic">Guest Checkout</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 hidden sm:table-cell">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider 
                                        {{ $order->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 
                                           ($order->status === 'pending' ? 'bg-amber-100 text-amber-700' : 
                                           ($order->status === 'cancelled' ? 'bg-rose-100 text-rose-700' : 'bg-blue-100 text-blue-700')) }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-gray-900 whitespace-nowrap">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    No recent orders found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sidebar Content --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-gray-900">Latest Messages</h3>
                    <a href="{{ route('superuser.contact-messages.index') }}" class="text-xs text-brand-600 hover:text-brand-700 font-medium">View all</a>
                </div>
                <div class="space-y-4">
                    @forelse($recent_messages as $message)
                    <div class="p-4 rounded-xl border {{ $message->is_read ? 'border-gray-100 bg-gray-50' : 'border-brand-100 bg-brand-50/30' }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-gray-900 text-sm truncate pr-2">{{ $message->name }}</span>
                            <span class="text-xs text-gray-500 shrink-0">{{ $message->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-600 line-clamp-2">{{ $message->message }}</p>
                    </div>
                    @empty
                    <div class="text-center py-6 text-gray-500 text-sm">
                        No recent messages.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
