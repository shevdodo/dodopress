<x-layouts.dashboard title="Products Management">
    <div x-data="bulkSelect()" class="mb-8">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Products</h2>
                <p class="text-sm text-gray-500 mt-1">Manage your catalog and inventory.</p>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-2">
                <form action="{{ route('superuser.products.import') }}" method="POST" enctype="multipart/form-data" class="hidden" id="importForm">
                    @csrf
                    <input type="file" name="import_file" id="importFileInput" accept=".xlsx,.csv" onchange="if(this.files.length) { document.getElementById('importForm').submit(); }">
                </form>
                <button type="button" onclick="document.getElementById('importFileInput').click();" class="bg-blue-600 text-white px-4 py-2.5 rounded-xl font-medium shadow-lg shadow-blue-600/30 hover:bg-blue-700 transition flex items-center space-x-2 inline-flex" title="Import Excel/CSV">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span>Import</span>
                </button>
                <a href="{{ route('superuser.products.export', ['search' => request('search'), 'category_id' => request('category_id')]) }}" class="bg-emerald-600 text-white px-4 py-2.5 rounded-xl font-medium shadow-lg shadow-emerald-600/30 hover:bg-emerald-700 transition flex items-center space-x-2 inline-flex">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span>Export</span>
                </a>
                <a href="{{ route('superuser.products.create') }}" class="bg-brand-600 text-white px-4 py-2.5 rounded-xl font-medium shadow-lg shadow-brand-600/30 hover:bg-brand-700 transition flex items-center space-x-2 inline-flex">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    <span>Add</span>
                </a>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
            <form action="{{ route('superuser.products.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-grow">
                    <div class="relative">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-brand-500 focus:ring-brand-500">
                    </div>
                </div>
                <div class="w-full sm:w-48">
                    <select name="category_id" class="w-full py-2 pl-3 pr-8 border border-gray-200 rounded-lg text-sm focus:border-brand-500 focus:ring-brand-500 text-gray-700">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-900 transition whitespace-nowrap">Filter</button>
                @if(request('search') || request('category_id'))
                    <a href="{{ route('superuser.products.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition text-center whitespace-nowrap">Clear</a>
                @endif
            </form>
        </div>

        @if (session('status'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-medium">
                {{ session('status') }}
            </div>
        @endif

        @if($products->count() > 0)
            {{-- Bulk Action Bar --}}
            <div x-show="selectedCount > 0" x-transition
                class="mb-4 px-4 py-3 bg-brand-50 border border-brand-200 rounded-xl flex items-center justify-between gap-4">
                <p class="text-sm font-medium text-brand-800">
                    <span x-text="selectedCount"></span> produk dipilih
                </p>
                <div class="flex gap-2">
                    <!-- Tombol Edit Massal -->
                    <button type="button" @click="showEditModal = true"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-brand-300 hover:bg-brand-100 text-brand-700 text-sm font-semibold rounded-lg transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit yang Dipilih
                    </button>

                    <!-- Tombol Hapus Massal -->
                    <form action="{{ route('superuser.products.bulk-destroy') }}" method="POST" @submit.prevent="confirmBulkDelete($el)">
                        @csrf
                        @method('DELETE')
                        <template x-for="id in selectedIds" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>

            <!-- Modal Edit Massal -->
            <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-transition.opacity>
                <div @click.away="showEditModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden flex flex-col" x-transition>
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="text-lg font-bold text-gray-900">Bulk Edit Produk</h3>
                        <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <form action="{{ route('superuser.products.bulk-update') }}" method="POST" class="p-6">
                        @csrf
                        @method('PUT')
                        <template x-for="id in selectedIds" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Update Kategori (Opsional)</label>
                                <select name="bulk_category_id" class="w-full border-gray-300 rounded-lg focus:ring-brand-500 focus:border-brand-500 text-sm">
                                    <option value="">-- Jangan Ubah --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Update Status (Opsional)</label>
                                <select name="bulk_status" class="w-full border-gray-300 rounded-lg focus:ring-brand-500 focus:border-brand-500 text-sm">
                                    <option value="">-- Jangan Ubah --</option>
                                    <option value="available">Available (Aktif)</option>
                                    <option value="unavailable">Unavailable (Nonaktif)</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Update Harga (Opsional)</label>
                                <input type="number" name="bulk_price" placeholder="Biarkan kosong jika tidak diubah" class="w-full border-gray-300 rounded-lg focus:ring-brand-500 focus:border-brand-500 text-sm">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Update Stok (Opsional)</label>
                                <input type="number" name="bulk_stock" placeholder="Biarkan kosong jika tidak diubah" class="w-full border-gray-300 rounded-lg focus:ring-brand-500 focus:border-brand-500 text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Update Status Pre Order (Opsional)</label>
                                <select name="bulk_is_preorder" class="w-full border-gray-300 rounded-lg focus:ring-brand-500 focus:border-brand-500 text-sm">
                                    <option value="">-- Jangan Ubah --</option>
                                    <option value="1">Aktifkan Pre Order</option>
                                    <option value="0">Matikan Pre Order</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <button type="button" @click="showEditModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Batal</button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-brand-600 rounded-lg hover:bg-brand-700 transition shadow-sm">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100 text-sm">
                                {{-- Select All --}}
                                <th class="py-4 px-4 w-10">
                                    <input type="checkbox"
                                        @change="toggleAll($event)"
                                        :checked="allSelected"
                                        :indeterminate="selectedCount > 0 && !allSelected"
                                        class="w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500 cursor-pointer">
                                </th>
                                <th class="py-4 px-4 font-semibold text-gray-600">Product</th>
                                <th class="py-4 px-4 font-semibold text-gray-600">Price</th>
                                <th class="py-4 px-4 font-semibold text-gray-600">Category</th>
                                <th class="py-4 px-4 font-semibold text-gray-600">Stock</th>
                                <th class="py-4 px-4 font-semibold text-gray-600">Status</th>
                                <th class="py-4 px-4 font-semibold text-gray-600 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($products as $product)
                                <tr class="hover:bg-gray-50/50 transition duration-150"
                                    :class="isSelected({{ $product->id }}) ? 'bg-red-50/40' : ''">
                                    <td class="py-4 px-4">
                                        <input type="checkbox"
                                            value="{{ $product->id }}"
                                            @change="toggle({{ $product->id }})"
                                            :checked="isSelected({{ $product->id }})"
                                            class="w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500 cursor-pointer">
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center space-x-3">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" class="w-10 h-10 rounded-lg object-cover border border-gray-200" alt="{{ $product->name }}">
                                            @else
                                                <div class="w-10 h-10 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                                <div class="flex items-center space-x-2 text-xs text-gray-500 mt-0.5">
                                                    <span>{{ $product->slug }}</span>
                                                    @if($product->weight)
                                                        <span>•</span>
                                                        <span>{{ $product->weight }}g</span>
                                                    @endif
                                                    @if($product->sizes)
                                                        <span>•</span>
                                                        <span class="bg-gray-100 px-1 py-0.5 rounded text-gray-600">Sizes: {{ $product->sizes }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-gray-800 font-medium">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="py-4 px-4 text-gray-500 text-sm">{{ $product->category ? $product->category->name : '-' }}</td>
                                    <td class="py-4 px-4 text-sm font-medium">
                                        @if($product->stock > 0)
                                            <span class="text-gray-950">{{ $product->stock }}</span>
                                        @else
                                            <span class="text-red-600 bg-red-50 px-1.5 py-0.5 rounded">Habis</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-sm">
                                        @if($product->status == 'available')
                                            <span class="px-2 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-medium">Available</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">Unavailable</span>
                                        @endif
                                        @if($product->is_preorder)
                                            <span class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs font-medium inline-block mt-1">Pre Order</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-right space-x-3">
                                        <a href="{{ route('superuser.products.edit', $product) }}" class="text-brand-600 hover:text-brand-800 font-medium text-sm inline-flex items-center">Edit</a>
                                        <form action="{{ route('superuser.products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 font-medium text-sm inline-flex items-center">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($products->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-center mx-auto mb-4 text-gray-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">No products found</h3>
                <p class="text-gray-500 mt-1">Start adding products to your catalog.</p>
            </div>
        @endif
    </div>

    <script>
        function bulkSelect() {
            return {
                showEditModal: false,
                selectedIds: [],
                allIds: @json($products->pluck('id')),

                get selectedCount() { return this.selectedIds.length; },
                get allSelected() { return this.allIds.length > 0 && this.selectedIds.length === this.allIds.length; },

                isSelected(id) { return this.selectedIds.includes(id); },

                toggle(id) {
                    if (this.isSelected(id)) {
                        this.selectedIds = this.selectedIds.filter(i => i !== id);
                    } else {
                        this.selectedIds.push(id);
                    }
                },

                toggleAll(event) {
                    this.selectedIds = event.target.checked ? [...this.allIds] : [];
                },

                confirmBulkDelete(form) {
                    if (this.selectedCount === 0) return;
                    if (confirm(`Hapus ${this.selectedCount} produk yang dipilih? Tindakan ini tidak bisa dibatalkan.`)) {
                        form.submit();
                    }
                }
            }
        }
    </script>
</x-layouts.dashboard>
