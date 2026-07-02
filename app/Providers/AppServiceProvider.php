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
        // Force HTTPS in production (needed for Cloudflare / reverse proxy environments)
        if ($this->app->environment('production')) {
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
                        'redirect' => '/auth/google/callback',
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Silently ignore DB errors during initial setup or migrations
        }
    }
}
