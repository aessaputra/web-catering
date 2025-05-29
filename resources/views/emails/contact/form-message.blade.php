@component('mail::message')
    # Pesan Kontak Baru Diterima

    Anda telah menerima pesan baru dari formulir kontak di website **{{ config('app.name') }}**.

    **Detail Pengirim:**
    - **Nama:** {{ $name }}
    - **Email:** <a href="mailto:{{ $email }}">{{ $email }}</a>

    ---

    **Isi Pesan:**

    {!! nl2br(e($messageContent)) !!}

    ---

    Harap segera tindak lanjuti pesan ini.

    Terima kasih,
    Tim Sistem {{ config('app.name') }}
@endcomponent
