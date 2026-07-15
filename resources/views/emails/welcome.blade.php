<x-mail::message>
# Halo {{ $userName }},

Selamat datang di **{{ $appName }}**! 🎉

Kami sangat senang Anda telah bergabung dengan kami. Akun Anda telah berhasil dibuat, dan Anda sekarang dapat mulai berbelanja koleksi batik terbaik kami.

<x-mail::button :url="route('dashboard')">
Ke Dashboard Akun Anda
</x-mail::button>

Jika Anda membutuhkan bantuan atau memiliki pertanyaan, jangan ragu untuk menghubungi kami.

Terima kasih,<br>
**Tim {{ $appName }}**
</x-mail::message>
