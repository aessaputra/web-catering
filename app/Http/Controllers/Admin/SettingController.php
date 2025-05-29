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
        'contact_whatsapp',
        'address',
        'instagram_url',
        'facebook_url',
        'Maps_url', // Key yang Anda gunakan untuk Google Maps URL
        'homepage_promotion_message',
    ];

    public function index()
    {
        $settingsFromDB = Setting::whereIn('key', $this->settingKeys)
            ->pluck('value', 'key');

        $settings = [];
        foreach ($this->settingKeys as $key) {
            $settings[$key] = $settingsFromDB->get($key, '');
        }

        return view('admin.settings.index', compact('settings'));
    }

    public function store(UpdateSettingsRequest $request)
    {
        // Validated akan mengembalikan array ['settings' => [...] ]
        $validatedSettingsInput = $request->validated()['settings'];

        foreach ($validatedSettingsInput as $key => $value) {
            if (in_array($key, $this->settingKeys)) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value ?? '']
                );
            }
        }

        // Cache::forget('site_settings'); // Bersihkan cache jika ada

        Alert::success('Berhasil!', 'Pengaturan berhasil diperbarui.');
        return redirect()->route('admin.settings.index');
    }
}
