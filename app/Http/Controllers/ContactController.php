<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'phone'   => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:150',
            'message' => 'required|string|max:5000',
        ], [
            'name.required'    => 'Nama lengkap wajib diisi.',
            'email.required'   => 'Alamat email wajib diisi.',
            'email.email'      => 'Format email tidak valid.',
            'message.required' => 'Pesan wajib diisi.',
        ]);

        // Kirim email ke admin jika dikonfigurasi
        $adminEmail = Setting::where('key', 'admin_email')->value('value')
                   ?: Setting::where('key', 'contact_email')->value('value')
                   ?: config('mail.from.address');

        if ($adminEmail && config('mail.default') !== 'log') {
            try {
                Mail::send([], [], function ($mail) use ($validated, $adminEmail) {
                    $subject = $validated['subject'] ?? 'Pesan Kontak Baru';
                    $body  = "Nama    : {$validated['name']}\n";
                    $body .= "Email   : {$validated['email']}\n";
                    if (!empty($validated['phone'])) {
                        $body .= "Telepon : {$validated['phone']}\n";
                    }
                    $body .= "Subjek  : {$subject}\n\n";
                    $body .= "Pesan:\n{$validated['message']}";

                    $mail->to($adminEmail)
                         ->replyTo($validated['email'], $validated['name'])
                         ->subject("[Kontak] {$subject}")
                         ->text('emails.plain-text')
                         ->setBody($body, 'text/plain');
                });
            } catch (\Exception $e) {
                // Gagal kirim email tidak menghentikan proses — tetap tampilkan sukses
                \Log::warning('Contact form mail failed: ' . $e->getMessage());
            }
        }

        return back()->with('contact_success', 'Terima kasih! Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.');
    }
}
