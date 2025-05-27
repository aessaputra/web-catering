<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use RealRashid\SweetAlert\Facades\Alert;

class SettingController extends Controller
{
    // Definisikan key settings yang akan dikelola
    private $settingKeys = [
        'site_name',
        'site_description',
        'contact_email',
        'contact_phone',
        'address',
        'instagram_url',
        'facebook_url',
        'Maps_url',
        'homepage_promotion_message',
        // Tambahkan key lain di sini jika ada
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua settings yang relevan
        // Menggunakan pluck agar mudah diakses di view sebagai ['key' => 'value']
        $settings = Setting::whereIn('key', $this->settingKeys)
            ->pluck('value', 'key');

        // Pastikan semua key ada di collection, meskipun nilainya null
        // agar form tetap menampilkan semua field
        $definedSettings = [];
        foreach ($this->settingKeys as $key) {
            $definedSettings[$key] = $settings->get($key, ''); // Default value string kosong jika tidak ada
        }

        return view('admin.settings.index', ['settings' => $definedSettings]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(UpdateSettingsRequest $request) // Gunakan Form Request
    {
        $validatedData = $request->validated()['settings'];

        foreach ($validatedData as $key => $value) {
            if (in_array($key, $this->settingKeys)) { // Hanya simpan key yang terdefinisi
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value ?? ''] // Simpan string kosong jika value null
                );
            }
        }

        // Opsional: Bersihkan cache jika Anda men-cache settings
        // Cache::forget('site_settings'); // Sesuaikan nama cache key Anda

        Alert::success('Berhasil!', 'Pengaturan berhasil diperbarui.');

        return redirect()->route('admin.settings.index');
    }
}
