<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\ContactFormRequest;
use App\Models\Setting;
use App\Models\ContactMessage;
use App\Mail\ContactFormAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ContactController extends Controller
{
    /**
     * Display the contact page.
     */
    public function index()
    {
        $settingKeys = ['contact_email', 'contact_whatsapp', 'address', 'Maps_url',];
        $settings = Setting::whereIn('key', $settingKeys)->pluck('value', 'key')->all();

        // Pastikan semua key ada, meskipun nilainya kosong, agar tidak error di view
        foreach ($settingKeys as $key) {
            if (!isset($settings[$key])) {
                $settings[$key] = null;
            }
        }

        return view('public.contact', compact('settings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactFormRequest $request)
    {
        $data = $request->validated(); // Data sudah divalidasi

        $adminEmail = env('ADMIN_EMAIL_ADDRESS');
        if (empty($adminEmail)) {
            // Fallback jika ADMIN_EMAIL_ADDRESS tidak diset di .env,
            // Anda bisa mengambil dari settings database atau email default.
            $adminEmailSetting = Setting::where('key', 'admin_notification_email')->first();
            $adminEmail = $adminEmailSetting ? $adminEmailSetting->value : config('mail.from.address');
            Log::warning('ADMIN_EMAIL_ADDRESS tidak diset di .env, menggunakan fallback: ' . $adminEmail);
        }


        DB::beginTransaction(); // Mulai transaksi untuk jaga-jaga jika ada proses yang mungkin gagal

        try {
            // Opsi 2: Simpan Pesan ke Database
            ContactMessage::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'message' => $data['message'],
                'is_read' => false, // Default pesan belum dibaca
            ]);
            Log::info('Pesan Kontak dari ' . $data['email'] . ' berhasil disimpan ke database.');

            // Opsi 1: Kirim Pesan ke Email Admin
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new ContactFormAlert($data));
                Log::info('Email notifikasi kontak untuk admin ' . $adminEmail . ' berhasil dikirim (atau diantrikan).');
            } else {
                Log::error('Tidak ada alamat email admin tujuan untuk notifikasi pesan kontak.');
            }

            DB::commit(); // Semua berhasil, commit transaksi

            Alert::success('Pesan Terkirim!', 'Terima kasih, ' . e($data['name']) . '! Pesan Anda telah berhasil kami terima dan akan segera kami tindak lanjuti.');
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan transaksi jika ada error
            Log::error('Gagal memproses atau mengirim pesan kontak: ' . $e->getMessage(), ['exception_trace' => $e->getTraceAsString()]);
            Alert::error('Gagal!', 'Maaf, terjadi kesalahan saat mengirim pesan Anda. Silakan coba lagi nanti atau hubungi kami langsung.');
        }

        return redirect()->route('contact.index');
    }
}
