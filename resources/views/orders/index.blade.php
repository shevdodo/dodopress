<x-layouts.dashboard title="My Orders">
    <div class="mb-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">My Orders</h2>
        <p class="text-sm text-gray-500 mt-1">View and track your previous orders.</p>
    </div>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 text-green-700 text-sm font-medium flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            {{ session('status') }}
        </div>
    @endif

    @if($orders->count() > 0)
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
                    <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-gray-100 pb-4 mb-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Order ID</p>
                            <p class="font-bold text-gray-900 text-lg">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Date</p>
                            <p class="font-semibold text-gray-900">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Status</p>
                            <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold uppercase tracking-wider mt-1">
                                {{ $order->status }}
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 font-medium">Total Amount</p>
                            <p class="font-bold text-brand-600 text-xl">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-xl text-sm text-gray-600">
                            <strong>Shipping Details:</strong> 
                            {{ $order->shipping_courier ? strtoupper($order->shipping_courier) : 'N/A' }} 
                            {{ $order->shipping_service ? '- ' . $order->shipping_service : '' }} 
                            (Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}) 
                            to {{ $order->destination_city ?: 'N/A' }}
                        </div>
                        
                        <div>
                            <h4 class="font-bold text-gray-900 mb-3 text-sm">Order Items</h4>
                            <div class="space-y-3">
                                @foreach($order->items as $item)
                                    <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-2">
                                        <div class="flex items-center space-x-3">
                                            <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">{{ $item->quantity }}x</span>
                                            <span class="font-medium text-gray-800">{{ $item->product_name }}</span>
                                        </div>
                                        <span class="text-gray-600">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if($order->status === 'pending')
                            <div class="mt-6 border-t border-gray-100 pt-6">
                                <h4 class="font-bold text-brand-700 mb-4 text-base">Instruksi Pembayaran</h4>
                                <div class="bg-brand-50 rounded-2xl p-5 border border-brand-100">
                                    <p class="text-sm text-gray-700 mb-4">Silakan lakukan pembayaran sebesar <strong class="text-brand-700 text-lg">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong> ke salah satu opsi di bawah ini:</p>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        @if(!empty($bankAccounts))
                                            <div>
                                                <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Transfer Bank</h5>
                                                <div class="text-sm text-gray-800 leading-relaxed font-medium bg-white p-3 rounded-lg border border-gray-200">
                                                    {!! nl2br(e($bankAccounts)) !!}
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if(!empty($qrisImage))
                                            <div>
                                                <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">QRIS</h5>
                                                <div class="bg-white p-3 rounded-lg border border-gray-200 text-center">
                                                    <img src="{{ Storage::url($qrisImage) }}" alt="QRIS" class="max-h-48 mx-auto rounded-lg">
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    @php
                                        $waText = "Halo, saya ingin mengkonfirmasi pembayaran untuk pesanan saya:\n\nOrder ID: *{$order->order_number}*\nTotal: *Rp " . number_format($order->total_amount, 0, ',', '.') . "*\n\nBerikut saya lampirkan bukti pembayarannya.";
                                        $waLink = "https://wa.me/" . preg_replace('/[^0-9]/', '', $whatsappNumber) . "?text=" . urlencode($waText);
                                    @endphp

                                    <a href="{{ $waLink }}" target="_blank" class="inline-flex items-center justify-center w-full sm:w-auto px-6 py-3 bg-[#25D366] hover:bg-[#1da851] text-white font-bold rounded-xl shadow-lg shadow-[#25D366]/30 transition transform hover:-translate-y-0.5">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.898-4.45 9.898-9.892 0-2.64-1.02-5.119-2.894-6.992-1.873-1.873-4.361-2.905-7.003-2.905-5.446 0-9.896 4.451-9.896 9.893 0 2.113.553 4.146 1.603 5.945l-1.164 4.257 4.406-1.164zm1.187-8.158c-.144-.405-.298-.415-.415-.422-.104-.006-.226-.006-.35-.006-.124 0-.327.047-.498.233-.171.186-.653.638-.653 1.556 0 .918.669 1.806.762 1.93.093.124 1.317 2.012 3.19 2.774.446.182.793.291 1.065.372.449.144.858.123 1.184.075.364-.055 1.119-.457 1.277-.9.158-.443.158-.823.111-.9-.047-.078-.171-.124-.358-.218-.186-.093-1.119-.553-1.292-.616-.173-.063-.298-.093-.422.093-.124.186-.498.616-.612.74-.114.124-.228.14-.415.047-.186-.093-.799-.295-1.521-.94-.562-.503-.941-1.124-1.055-1.31-.114-.186-.012-.287.081-.38.083-.082.186-.217.28-.325.093-.109.124-.186.186-.31.063-.124.031-.233-.016-.326-.046-.093-.421-1.017-.575-1.391z"/></svg>
                                        Konfirmasi Pembayaran via WhatsApp
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center shadow-sm">
            <div class="w-16 h-16 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-center mx-auto mb-4 text-gray-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">No Orders Yet</h3>
            <p class="text-gray-500 mb-6 max-w-sm mx-auto">You haven't placed any orders. Start exploring our collection and find something you love!</p>
            <a href="{{ route('landing') }}" class="inline-block bg-brand-600 text-white font-bold px-8 py-3 rounded-xl hover:bg-brand-700 shadow-lg shadow-brand-600/30 transition">
                Start Shopping
            </a>
        </div>
    @endif
</x-layouts.dashboard>
