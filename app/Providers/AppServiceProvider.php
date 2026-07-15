<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS on live server (needed for Cloudflare / reverse proxy environments)
        if (request()->getHost() !== 'localhost' && request()->getHost() !== '127.0.0.1') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Dynamically load Google OAuth settings
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $googleEnabled = \Illuminate\Support\Facades\DB::table('settings')->where('key', 'api_google_oauth_enabled')->value('value');
                if ($googleEnabled == '1') {
                    $clientId = \Illuminate\Support\Facades\DB::table('settings')->where('key', 'api_google_client_id')->value('value');
                    $clientSecret = \Illuminate\Support\Facades\DB::table('settings')->where('key', 'api_google_client_secret')->value('value');
                    
                    \Illuminate\Support\Facades\Config::set('services.google', [
                        'client_id' => $clientId,
                        'client_secret' => $clientSecret,
                        'redirect' => url('/auth/google/callback'),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Silently ignore DB errors during initial setup or migrations
        }
    }
}
