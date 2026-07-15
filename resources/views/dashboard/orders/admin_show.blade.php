<x-layouts.dashboard title="Order Details">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="min-w-0 flex-1">
            <div class="flex items-center space-x-3 min-w-0">
                <a href="{{ route('superuser.orders.index') }}" class="text-gray-400 hover:text-brand-600 transition shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </a>
                <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 truncate">{{ 'Order ' . $order->order_number }}</h2>
            </div>
            <p class="text-sm text-gray-500 mt-2 sm:mt-1 sm:ml-9">Placed on {{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <div class="shrink-0">
            <span class="inline-block px-4 py-2 bg-yellow-100 text-yellow-800 rounded-xl text-sm font-bold uppercase tracking-wider">
                Status: {{ $order->status }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-5 sm:p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-3">Order Items</h3>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center space-x-3 sm:space-x-4 min-w-0 flex-1">
                                @if($item->product && $item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" class="w-12 h-12 sm:w-16 sm:h-16 object-cover rounded-xl shrink-0 border border-gray-100 shadow-sm" alt="{{ $item->product_name }}">
                                @else
                                    <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center font-bold text-gray-400 text-[10px] sm:text-xs shrink-0 text-center leading-tight">No Img</div>
                                @endif
                                
                                <div class="min-w-0 flex-1">
                                    <p class="font-bold text-gray-900 text-sm sm:text-base leading-snug truncate">{{ $item->product_name }}</p>
                                    <p class="text-xs sm:text-sm text-gray-500 mt-1 truncate">{{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="font-bold text-gray-900 text-sm sm:text-base shrink-0 text-right">
                                Rp {{ number_format($item->total, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-100 space-y-3">
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Subtotal</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Shipping Cost ({{ $order->shipping_courier ? strtoupper($order->shipping_courier) : 'N/A' }})</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg sm:text-xl font-bold text-brand-600 pt-3 border-t border-gray-100 mt-2">
                        <span>Total Amount</span>
                        <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Details -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-5 sm:p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-3">Customer Information</h3>
                @if($order->user)
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-semibold tracking-wider mb-1">Name</p>
                            <p class="font-medium text-gray-900 text-sm sm:text-base">{{ $order->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-semibold tracking-wider mb-1">Email</p>
                            <a href="mailto:{{ $order->user->email }}" class="font-medium text-brand-600 hover:underline text-sm sm:text-base break-all">{{ $order->user->email }}</a>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">Guest Checkout</p>
                @endif
            </div>

            <!-- Shipping Info -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-5 sm:p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-3">Shipping Details</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold tracking-wider mb-1">Destination City</p>
                        <p class="font-medium text-gray-900 text-sm sm:text-base">{{ $order->destination_city ?: 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold tracking-wider mb-1">Courier & Service</p>
                        <p class="font-medium text-gray-900 text-sm sm:text-base">
                            {{ $order->shipping_courier ? strtoupper($order->shipping_courier) : 'N/A' }} 
                            {{ $order->shipping_service ? '- ' . $order->shipping_service : '' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold tracking-wider mb-1">Payment Status</p>
                        <span class="inline-block px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-lg {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $order->payment_status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
