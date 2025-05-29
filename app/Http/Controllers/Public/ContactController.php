<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\ContactFormRequest; // Import Form Request
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Untuk logging
use Illuminate\Support\Facades\Mail; // Jika ingin mengirim email
// use App\Mail\ContactFormMail; // Buat Mailable jika perlu

class ContactController extends Controller
{
    /**
     * Display the contact page.
     */
    public function index()
    {
        $settings = Setting::whereIn('key', [
            'contact_email',
            'contact_whatsapp',
            'address',
            'Maps_url'
        ])->pluck('value', 'key');

        return view('public.contact', compact('settings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactFormRequest $request)
    {
        // Data sudah divalidasi oleh ContactFormRequest

        $data = $request->validated();

        // Opsi 1: Simpan ke database (buat model dan migrasi untuk 'messages' jika perlu)
        // Message::create($data);

        // Opsi 2: Kirim email ke admin
        // Pastikan konfigurasi mail Anda sudah benar di .env
        // try {
        //     Mail::to(config('mail.from.address'))->send(new ContactFormMail($data));
        // } catch (\Exception $e) {
        //     Log::error('Gagal mengirim email kontak: ' . $e->getMessage());
        //     // Mungkin tampilkan pesan error yang lebih umum ke pengguna
        //     return back()->with('error', 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi nanti.');
        // }

        // Untuk saat ini, kita hanya tampilkan pesan sukses dan log data
        Log::info('Pesan Kontak Diterima:', $data);

        return redirect()->route('contact.index')->with('success', 'Pesan Anda telah berhasil terkirim! Kami akan segera menghubungi Anda.');
    }
}
