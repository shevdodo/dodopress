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
