<x-layouts.dashboard>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Store Page Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-4 rounded-lg">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('superuser.settings.store.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6 max-w-2xl">
                        @csrf
                        
                        <!-- Store Title -->
                        <div>
                            <label for="store_page_title" class="block text-sm font-medium text-gray-700">Judul Halaman Store</label>
                            <input type="text" name="store_page_title" id="store_page_title" value="{{ old('store_page_title', $settings['store_page_title'] ?? 'Our Products') }}" class="mt-1 block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-md shadow-sm">
                        </div>

                        <!-- Catalog Title -->
                        <div>
                            <label for="store_catalog_title" class="block text-sm font-medium text-gray-700">Judul Katalog</label>
                            <input type="text" name="store_catalog_title" id="store_catalog_title" value="{{ old('store_catalog_title', $settings['store_catalog_title'] ?? 'Katalog') }}" class="mt-1 block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-md shadow-sm">
                            <p class="mt-1 text-xs text-gray-500">Judul ini akan muncul di atas daftar produk (contoh: Katalog, Semua Produk).</p>
                        </div>

                        <!-- Store Subtitle -->
                        <div>
                            <label for="store_page_subtitle" class="block text-sm font-medium text-gray-700">Subjudul / Deskripsi Singkat</label>
                            <textarea name="store_page_subtitle" id="store_page_subtitle" rows="3" class="mt-1 block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-md shadow-sm">{{ old('store_page_subtitle', $settings['store_page_subtitle'] ?? 'Temukan koleksi lengkap produk kami.') }}</textarea>
                        </div>

                        <!-- Products per page -->
                        <div>
                            <label for="store_products_per_page" class="block text-sm font-medium text-gray-700">Jumlah Produk per Halaman</label>
                            <input type="number" name="store_products_per_page" id="store_products_per_page" min="1" max="100" value="{{ old('store_products_per_page', $settings['store_products_per_page'] ?? '12') }}" class="mt-1 block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-md shadow-sm">
                        </div>

                        <!-- Store Banner Image -->
                        <div>
                            <x-media-picker name="store_banner_image" current="{{ $settings['store_banner_image'] ?? '' }}" label="Banner Image (Opsional)" previewSize="lg" />
                        </div>

                        <hr class="border-gray-100 my-6">

                        <h3 class="text-lg font-semibold text-gray-800">Pengaturan Pembayaran & Kontak</h3>
                        <p class="text-sm text-gray-500 mb-4">Atur informasi kontak dan rekening pembayaran untuk pesanan manual.</p>

                        <!-- Whatsapp CS -->
                        <div>
                            <label for="whatsapp_cs_number" class="block text-sm font-medium text-gray-700">Nomor WhatsApp CS</label>
                            <p class="text-xs text-gray-500 mb-2">Nomor WhatsApp untuk tombol 'Tanya CS' di halaman produk dan konfirmasi pembayaran. Contoh: 6281329515082 (Gunakan kode negara 62, tanpa tanda +)</p>
                            <input type="text" id="whatsapp_cs_number" name="whatsapp_cs_number" value="{{ old('whatsapp_cs_number', $settings['whatsapp_cs_number'] ?? '6281329515082') }}" class="mt-1 block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-md shadow-sm">
                        </div>

                        <!-- Bank Accounts -->
                        <div>
                            <label for="bank_accounts" class="block text-sm font-medium text-gray-700">Rekening Bank (Manual Transfer)</label>
                            <p class="text-xs text-gray-500 mb-2">Masukkan informasi rekening bank Anda. Tiap rekening dipisahkan baris baru. (Contoh: BCA 1234567890 a.n. John Doe)</p>
                            <textarea name="bank_accounts" id="bank_accounts" rows="4" class="mt-1 block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-md shadow-sm">{{ old('bank_accounts', $settings['bank_accounts'] ?? '') }}</textarea>
                        </div>

                        <!-- QRIS Static Image -->
                        <div>
                            <x-media-picker name="qris_image" current="{{ $settings['qris_image'] ?? '' }}" label="QRIS Statis (Opsional)" previewSize="md" />
                            <p class="text-xs text-gray-500 mt-1">Upload gambar QRIS statis toko Anda untuk memudahkan pembayaran pelanggan.</p>
                        </div>

                        <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                            <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold py-2 px-6 rounded-lg shadow transition duration-150">
                                Save Store Settings
                            </button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
