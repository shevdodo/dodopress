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
                            <div class="mt-8 pt-8 border-t border-gray-100">
                                <div class="bg-gradient-to-br from-brand-50 to-white rounded-3xl p-6 sm:p-8 border border-brand-100 shadow-sm relative overflow-hidden">
                                    <!-- Decorative background -->
                                    <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-brand-100 rounded-full opacity-50 blur-3xl"></div>
                                    
                                    <div class="relative z-10">
                                        <div class="flex items-center space-x-3 mb-6">
                                            <div class="w-10 h-10 rounded-full bg-brand-100 flex items-center justify-center text-brand-600">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-900 text-lg">Menunggu Pembayaran</h4>
                                                <p class="text-sm text-gray-500">Silakan selesaikan pembayaran untuk memproses pesanan Anda.</p>
                                            </div>
                                        </div>

                                        <div class="flex flex-col lg:flex-row gap-6 mb-8">
                                            <!-- Left Column: Bank Accounts & Total -->
                                            <div class="flex-1 space-y-6">
                                                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500 font-medium mb-1">Total Tagihan</p>
                                                        <p class="text-3xl font-extrabold text-brand-600 tracking-tight">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                                    </div>
                                                    <span class="px-4 py-2 bg-yellow-50 text-yellow-700 rounded-xl text-sm font-bold border border-yellow-100 self-start sm:self-auto">
                                                        Status: PENDING
                                                    </span>
                                                </div>

                                                @if(!empty($bankAccounts))
                                                    <div class="space-y-3">
                                                        <h5 class="text-sm font-bold text-gray-700 flex items-center">
                                                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                                                            Transfer Bank Manual
                                                        </h5>
                                                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm h-full">
                                                            <div class="prose prose-sm prose-gray max-w-none font-medium leading-loose text-base">
                                                                {!! nl2br(e($bankAccounts)) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Right Column: QRIS -->
                                            @if(!empty($qrisImage))
                                                <div class="w-full lg:w-[320px] shrink-0 space-y-3">
                                                    <h5 class="text-sm font-bold text-gray-700 flex items-center">
                                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                                        Scan QRIS
                                                    </h5>
                                                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center">
                                                        <img src="{{ Storage::url($qrisImage) }}" alt="QRIS" class="w-full max-w-[240px] rounded-xl object-contain">
                                                        <p class="text-xs text-center text-gray-400 mt-4">Scan menggunakan aplikasi mobile banking atau e-wallet Anda.</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        @php
                                            $waText = "Halo, saya ingin mengkonfirmasi pembayaran untuk pesanan saya:\n\nOrder ID: *{$order->order_number}*\nTotal: *Rp " . number_format($order->total_amount, 0, ',', '.') . "*\n\nBerikut saya lampirkan bukti pembayarannya.";
                                            $waLink = "https://wa.me/" . preg_replace('/[^0-9]/', '', $whatsappNumber) . "?text=" . urlencode($waText);
                                        @endphp

                                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-brand-100/50">
                                            <p class="text-sm text-gray-500 w-full sm:max-w-md text-center sm:text-left">
                                                Setelah melakukan pembayaran, klik tombol di samping untuk mengirim bukti transfer via WhatsApp agar pesanan segera diproses.
                                            </p>
                                            <a href="{{ $waLink }}" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 bg-[#25D366] hover:bg-[#1da851] text-white font-bold rounded-2xl shadow-lg shadow-[#25D366]/20 transition transform hover:-translate-y-1">
                                                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.898-4.45 9.898-9.892 0-2.64-1.02-5.119-2.894-6.992-1.873-1.873-4.361-2.905-7.003-2.905-5.446 0-9.896 4.451-9.896 9.893 0 2.113.553 4.146 1.603 5.945l-1.164 4.257 4.406-1.164zm1.187-8.158c-.144-.405-.298-.415-.415-.422-.104-.006-.226-.006-.35-.006-.124 0-.327.047-.498.233-.171.186-.653.638-.653 1.556 0 .918.669 1.806.762 1.93.093.124 1.317 2.012 3.19 2.774.446.182.793.291 1.065.372.449.144.858.123 1.184.075.364-.055 1.119-.457 1.277-.9.158-.443.158-.823.111-.9-.047-.078-.171-.124-.358-.218-.186-.093-1.119-.553-1.292-.616-.173-.063-.298-.093-.422.093-.124.186-.498.616-.612.74-.114.124-.228.14-.415.047-.186-.093-.799-.295-1.521-.94-.562-.503-.941-1.124-1.055-1.31-.114-.186-.012-.287.081-.38.083-.082.186-.217.28-.325.093-.109.124-.186.186-.31.063-.124.031-.233-.016-.326-.046-.093-.421-1.017-.575-1.391z"/></svg>
                                                Konfirmasi WhatsApp
                                            </a>
                                        </div>
                                    </div>
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
