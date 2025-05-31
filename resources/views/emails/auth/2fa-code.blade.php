@component('mail::message')
    # Kode Verifikasi Dua Langkah (2FA) Anda

    Halo,

    Seseorang baru saja mencoba untuk login ke akun admin Anda di
    {{ config('app.name', 'Nama Aplikasi Default Anda') }}.

    Untuk menyelesaikan proses login, silakan gunakan kode verifikasi sekali pakai di bawah ini:

    {{ $code }}

    Kode ini hanya berlaku selama {{ config('auth.2fa_code_lifetime', 3) }} menit dan hanya dapat digunakan satu kali.

    Jika Anda tidak merasa melakukan upaya login ini, mohon abaikan email ini.

    Terima kasih atas perhatian Anda terhadap keamanan akun.

    Salam hormat,
    Tim Keamanan {{ config('app.name', 'Nama Aplikasi Default Anda') }}
@endcomponent
