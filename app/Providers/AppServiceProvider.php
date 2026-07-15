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

                // Dynamically load SMTP settings
                $mailHost = \Illuminate\Support\Facades\DB::table('settings')->where('key', 'mail_host')->value('value');
                if (!empty($mailHost)) {
                    \Illuminate\Support\Facades\Config::set('mail.default', 'smtp');
                    \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.host', $mailHost);
                    \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.port', \Illuminate\Support\Facades\DB::table('settings')->where('key', 'mail_port')->value('value') ?: 587);
                    \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.username', \Illuminate\Support\Facades\DB::table('settings')->where('key', 'mail_username')->value('value'));
                    \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.password', \Illuminate\Support\Facades\DB::table('settings')->where('key', 'mail_password')->value('value'));
                    \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.encryption', 'tls'); // Brevo standard
                    
                    // Disable SSL verification to bypass aggressive shared hosting interception
                    \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.stream', [
                        'ssl' => [
                            'allow_self_signed' => true,
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                        ],
                    ]);
                    
                    $fromAddress = \Illuminate\Support\Facades\DB::table('settings')->where('key', 'mail_from_address')->value('value');
                    if ($fromAddress) {
                        \Illuminate\Support\Facades\Config::set('mail.from.address', $fromAddress);
                        \Illuminate\Support\Facades\Config::set('mail.from.name', \Illuminate\Support\Facades\DB::table('settings')->where('key', 'site_title')->value('value') ?: config('app.name'));
                    }
                }

                // Dynamically set App Name
                $siteTitle = \Illuminate\Support\Facades\DB::table('settings')->where('key', 'site_title')->value('value');
                if ($siteTitle) {
                    \Illuminate\Support\Facades\Config::set('app.name', $siteTitle);
                }
            }
        } catch (\Exception $e) {
            // Silently ignore DB errors during initial setup or migrations
        }
    }
}
