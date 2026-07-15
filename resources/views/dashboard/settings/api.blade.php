<x-layouts.dashboard title="API Settings">
    <div class="mb-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">API Settings</h2>
        <p class="text-sm text-gray-500 mt-1">Manage external API integrations for shipping and payments.</p>
    </div>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 text-green-700 text-sm font-medium flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('superuser.settings.api.update') }}">
        @csrf
        <div class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-sm mb-6 space-y-8 max-w-3xl">
            
            <!-- Shipping API -->
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">API Ongkos Kirim (Shipping)</h3>
                    <p class="text-sm text-gray-500 max-w-md">Enable this to automatically calculate shipping costs based on the customer's location using a third-party shipping API (e.g. RajaOngkir).</p>
                </div>
                <div class="ml-4 pt-1">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="api_shipping_enabled" value="1" class="sr-only peer" {{ (isset($settings['api_shipping_enabled']) && $settings['api_shipping_enabled'] == '1') ? 'checked' : '' }}>
                        <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-brand-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-brand-600"></div>
                    </label>
                </div>
            </div>
            
            <div class="mt-4">
                <label for="api_shipping_key" class="block text-sm font-semibold text-gray-800 mb-1">Shipping API Key</label>
                <input type="text" id="api_shipping_key" name="api_shipping_key" value="{{ old('api_shipping_key', $settings['api_shipping_key'] ?? '') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 font-mono text-sm" placeholder="Enter your Shipping API Key (e.g., RajaOngkir API Key)">
            </div>
            
            <div class="mt-4 p-4 rounded-xl border border-gray-100 bg-gray-50/50">
                <label class="block text-sm font-semibold text-gray-800 mb-3">Enabled Couriers</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @php
                        $enabledCouriers = explode(',', $settings['api_shipping_couriers'] ?? 'jne,pos,tiki');
                        $availableCouriers = [
                            'jne' => 'JNE',
                            'pos' => 'POS Indonesia',
                            'tiki' => 'TIKI',
                            'jnt' => 'J&T Express',
                            'sicepat' => 'SiCepat',
                            'anteraja' => 'AnterAja',
                            'ninja' => 'Ninja Xpress',
                            'ide' => 'ID Express',
                            'lion' => 'Lion Parcel'
                        ];
                    @endphp
                    @foreach($availableCouriers as $code => $name)
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="api_shipping_couriers[]" value="{{ $code }}" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500" {{ in_array($code, $enabledCouriers) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">{{ $name }}</span>
                    </label>
                    @endforeach
                </div>
                <p class="text-xs text-gray-500 mt-3">Select which couriers should be available to customers on the checkout page.</p>
            </div>

            <div class="mt-4">
                <label for="store_city_id" class="block text-sm font-semibold text-gray-800 mb-1">Origin City ID (ID Kota Asal Pengiriman)</label>
                <input type="number" id="store_city_id" name="store_city_id" value="{{ old('store_city_id', $settings['store_city_id'] ?? '445') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 text-sm" placeholder="Contoh: 445">
                <p class="text-xs text-gray-500 mt-1">Masukkan ID Kota asal toko Anda berdasarkan ID RajaOngkir. Contoh: <strong>445</strong> untuk Kota Surakarta (Solo), <strong>114</strong> untuk Kota Denpasar.</p>
            </div>

            <hr class="border-gray-100">

            <!-- Payment API -->
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">API Payment Gateway</h3>
                    <p class="text-sm text-gray-500 max-w-md">Enable this to accept automated online payments (Credit Card, Virtual Account, e-Wallet) via a Payment Gateway (e.g. Midtrans, Xendit).</p>
                </div>
                <div class="ml-4 pt-1">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="api_payment_enabled" value="1" class="sr-only peer" {{ (isset($settings['api_payment_enabled']) && $settings['api_payment_enabled'] == '1') ? 'checked' : '' }}>
                        <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-brand-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-brand-600"></div>
                    </label>
                </div>
            </div>

            <div class="mt-4 space-y-4">
                <div>
                    <label for="api_payment_server_key" class="block text-sm font-semibold text-gray-800 mb-1">Server Key (Secret Key)</label>
                    <input type="text" id="api_payment_server_key" name="api_payment_server_key" value="{{ old('api_payment_server_key', $settings['api_payment_server_key'] ?? '') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 font-mono text-sm" placeholder="Enter Server Key">
                </div>
                <div>
                    <label for="api_payment_client_key" class="block text-sm font-semibold text-gray-800 mb-1">Client Key (Public Key)</label>
                    <input type="text" id="api_payment_client_key" name="api_payment_client_key" value="{{ old('api_payment_client_key', $settings['api_payment_client_key'] ?? '') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 font-mono text-sm" placeholder="Enter Client Key">
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- SMTP Mail Server -->
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">SMTP Mail Server (Email Sender)</h3>
                    <p class="text-sm text-gray-500 max-w-md">Configure your SMTP server (e.g., Brevo, Mailtrap) to send automated emails like Welcome emails or order confirmations.</p>
                </div>
            </div>

            <div class="mt-4 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="mail_host" class="block text-sm font-semibold text-gray-800 mb-1">Mail Host (Server)</label>
                        <input type="text" id="mail_host" name="mail_host" value="{{ old('mail_host', $settings['mail_host'] ?? '') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 text-sm font-mono" placeholder="e.g. smtp-relay.brevo.com">
                    </div>
                    <div>
                        <label for="mail_port" class="block text-sm font-semibold text-gray-800 mb-1">Mail Port</label>
                        <input type="text" id="mail_port" name="mail_port" value="{{ old('mail_port', $settings['mail_port'] ?? '587') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 text-sm font-mono" placeholder="e.g. 587">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="mail_username" class="block text-sm font-semibold text-gray-800 mb-1">Mail Username (Login)</label>
                        <input type="text" id="mail_username" name="mail_username" value="{{ old('mail_username', $settings['mail_username'] ?? '') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 text-sm font-mono" placeholder="Enter SMTP Username">
                    </div>
                    <div>
                        <label for="mail_password" class="block text-sm font-semibold text-gray-800 mb-1">Mail Password (API Key)</label>
                        <input type="password" id="mail_password" name="mail_password" value="{{ old('mail_password', $settings['mail_password'] ?? '') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 text-sm font-mono" placeholder="Enter SMTP Password/Key">
                    </div>
                </div>
                <div>
                    <label for="mail_from_address" class="block text-sm font-semibold text-gray-800 mb-1">Mail From Address</label>
                    <input type="email" id="mail_from_address" name="mail_from_address" value="{{ old('mail_from_address', $settings['mail_from_address'] ?? 'noreply@batikmukti.co.id') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 text-sm font-mono" placeholder="e.g. hello@batikmukti.co.id">
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- Google OAuth API -->
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Google OAuth 2.0 (Login/Register)</h3>
                    <p class="text-sm text-gray-500 max-w-md">Enable this to allow users to sign in or register using their Google account via Laravel Socialite.</p>
                </div>
                <div class="ml-4 pt-1">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="api_google_oauth_enabled" value="1" class="sr-only peer" {{ (isset($settings['api_google_oauth_enabled']) && $settings['api_google_oauth_enabled'] == '1') ? 'checked' : '' }}>
                        <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-brand-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-brand-600"></div>
                    </label>
                </div>
            </div>

            <div class="mt-4 space-y-4">
                <div>
                    <label for="api_google_client_id" class="block text-sm font-semibold text-gray-800 mb-1">Google Client ID</label>
                    <input type="text" id="api_google_client_id" name="api_google_client_id" value="{{ old('api_google_client_id', $settings['api_google_client_id'] ?? '') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 font-mono text-sm" placeholder="Enter Google Client ID">
                </div>
                <div>
                    <label for="api_google_client_secret" class="block text-sm font-semibold text-gray-800 mb-1">Google Client Secret</label>
                    <input type="text" id="api_google_client_secret" name="api_google_client_secret" value="{{ old('api_google_client_secret', $settings['api_google_client_secret'] ?? '') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 font-mono text-sm" placeholder="Enter Google Client Secret">
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-brand-600 text-white px-6 py-3 rounded-xl font-medium shadow-lg shadow-brand-600/30 hover:bg-brand-700 transition">
                    Save API Settings
                </button>
            </div>
        </div>
    </form>
</x-layouts.dashboard>
