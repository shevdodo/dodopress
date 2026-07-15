<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class OAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        // Cek apakah konfigurasi Google OAuth sudah diisi
        if (!config('services.google.client_id') || !config('services.google.client_secret')) {
            return redirect()->back()->withErrors(['email' => 'Fitur Google OAuth belum berfungsi secara penuh. Admin perlu mengatur Client ID dan Secret terlebih dahulu.']);
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user exists with this google_id
            $user = User::where('google_id', $googleUser->id)->first();
            
            if ($user) {
                // User exists, log them in
                Auth::login($user);
            } else {
                // Check if user exists with this email
                $existingUser = User::where('email', $googleUser->email)->first();
                
                if ($existingUser) {
                    // Update existing user with google_id
                    $existingUser->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $existingUser->avatar ?? $googleUser->avatar,
                    ]);
                    Auth::login($existingUser);
                } else {
                    // Create a new user
                    $newUser = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                        'password' => bcrypt(Str::random(16)), // Random password
                        'role' => 'user', // Default role
                        'email_verified_at' => now(), // Automatically verify email since Google already verified it
                    ]);
                    
                    // Send Welcome Email
                    try {
                        \Illuminate\Support\Facades\Mail::to($newUser->email)->send(new \App\Mail\WelcomeUserMail($newUser));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Failed to send welcome email (Google OAuth): ' . $e->getMessage());
                    }
                    
                    Auth::login($newUser);
                }
            }
            
            // Redirect to intended page or dashboard
            return redirect()->intended(route('dashboard', absolute: false));
            
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Gagal login menggunakan Google. Pastikan kredensial OAuth sudah diatur dengan benar di Settings.']);
        }
    }
}
