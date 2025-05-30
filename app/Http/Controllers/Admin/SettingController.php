<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Http\Requests\Admin\UpdateAboutPageSettingsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    private array $generalSettingKeys = [
        'site_name',
        'site_description',
        'contact_whatsapp',
        'address',
        'instagram_url',
        'facebook_url',
        'Maps_url',
        'site_logo',
        'hero_image_homepage',
        'operating_hours'
    ];

    private array $aboutPageSettingKeys = [
        'about_hero_title',
        'about_hero_subtitle_template',
        'about_history_title',
        'about_history_content',
        'about_vision_title',
        'about_vision_content',
        'about_mission_title',
        'about_mission_point_1',
        'about_mission_point_2',
        'about_mission_point_3',
        'about_mission_point_4',
        'about_team_title',
        'about_team_content_1',
        'about_team_content_2',
    ];

    private function getDefaultSettingValue(string $key): ?string
    {
        $defaults = [
            'site_name' => config('app.name', 'Catering Lezat'),
            'site_logo' => null,
            'hero_image_homepage' => null,
            'operating_hours' => "Senin - Sabtu: 08:00 - 23:00 WIB",
            'site_description' => 'Deskripsi default.',
            'contact_email' => 'info@default.com',
            'contact_whatsapp' => '08000000000',
            'address' => 'Alamat default.',
            'instagram_url' => '#',
            'facebook_url' => '#',
            'Maps_url' => '',

            'about_hero_title' => 'Tentang Kami',
            'about_hero_subtitle_template' => 'Mengenal Lebih Dekat {appName}',
            'about_history_title' => 'Perjalanan Kami',
            'about_history_content' => "Isi sejarah perusahaan di sini...",
            'about_vision_title' => 'Visi Kami',
            'about_vision_content' => 'Isi visi perusahaan di sini...',
            'about_mission_title' => 'Misi Kami',
            'about_mission_point_1' => 'Poin misi 1...',
            'about_mission_point_2' => 'Poin misi 2...',
            'about_mission_point_3' => 'Poin misi 3...',
            'about_mission_point_4' => 'Poin misi 4...',
            'about_team_title' => 'Tim Kami',
            'about_team_content_1' => 'Deskripsi tim paragraf 1...',
            'about_team_content_2' => 'Deskripsi tim paragraf 2...',
        ];
        return $defaults[$key] ?? '';
    }

    private function getSettingsForKeys(array $keys): array
    {
        $settingsFromDB = Setting::whereIn('key', $keys)->pluck('value', 'key');
        $settings = [];
        foreach ($keys as $key) {
            $settings[$key] = $settingsFromDB->get($key, $this->getDefaultSettingValue($key));
        }
        return $settings;
    }

    /**
     * Display the general website settings form.
     */
    public function generalSettingsIndex()
    {
        $settings = $this->getSettingsForKeys($this->generalSettingKeys);
        return view('admin.settings.general_index', compact('settings'));
    }

    /**
     * Store the general website settings.
     */
    public function storeGeneralSettings(UpdateSettingsRequest $request)
    {
        $validatedInputs = $request->validated();
        $textSettings = $validatedInputs['settings'] ?? [];

        DB::beginTransaction();
        try {
            if ($request->hasFile('site_logo_file')) {
                $oldLogoPath = Setting::where('key', 'site_logo')->first()?->value;
                if ($oldLogoPath && Storage::disk('public')->exists($oldLogoPath)) {
                    Storage::disk('public')->delete($oldLogoPath);
                }
                $file = $request->file('site_logo_file');
                $fileName = 'site_logo_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('settings', $fileName, 'public');
                Setting::updateOrCreate(['key' => 'site_logo'], ['value' => $path]);
            } elseif ($request->boolean('remove_current_logo')) {
                $oldLogoPath = Setting::where('key', 'site_logo')->first()?->value;
                if ($oldLogoPath && Storage::disk('public')->exists($oldLogoPath)) {
                    Storage::disk('public')->delete($oldLogoPath);
                }
                Setting::updateOrCreate(['key' => 'site_logo'], ['value' => '']);
            }

            if ($request->hasFile('hero_image_homepage_file')) {
                $oldHeroImagePath = Setting::where('key', 'hero_image_homepage')->first()?->value;
                if ($oldHeroImagePath && Storage::disk('public')->exists($oldHeroImagePath)) {
                    Storage::disk('public')->delete($oldHeroImagePath);
                }
                $file = $request->file('hero_image_homepage_file');
                $fileName = 'hero_home_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('settings', $fileName, 'public');
                Setting::updateOrCreate(['key' => 'hero_image_homepage'], ['value' => $path]);
            } elseif ($request->boolean('remove_current_hero_image')) {
                $oldHeroImagePath = Setting::where('key', 'hero_image_homepage')->first()?->value;
                if ($oldHeroImagePath && Storage::disk('public')->exists($oldHeroImagePath)) {
                    Storage::disk('public')->delete($oldHeroImagePath);
                }
                Setting::updateOrCreate(['key' => 'hero_image_homepage'], ['value' => '']);
            }

            if (!empty($textSettings)) {
                foreach ($textSettings as $key => $value) {
                    if (in_array($key, $this->generalSettingKeys) && !in_array($key, ['site_logo', 'hero_image_homepage'])) {
                        Setting::updateOrCreate(['key' => $key], ['value' => $value ?? '']);
                    }
                }
            }

            DB::commit();
            Cache::forget('site_global_settings');
            Alert::success('Berhasil!', 'Pengaturan umum berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan pengaturan umum: ' . $e->getMessage(), ['exception' => $e]);
            Alert::error('Gagal!', 'Terjadi kesalahan saat menyimpan pengaturan umum.');
        }

        return redirect()->route('admin.settings.general.index');
    }

    /**
     * Display the "About Us" page content settings form.
     */
    public function aboutPageSettingsIndex()
    {
        $settings = $this->getSettingsForKeys($this->aboutPageSettingKeys);
        return view('admin.settings.about_page_index', compact('settings'));
    }

    /**
     * Store the "About Us" page content settings.
     */
    public function storeAboutPageSettings(UpdateAboutPageSettingsRequest $request)
    {
        $validatedSettingsInput = $request->validated()['settings_about'];

        DB::beginTransaction();
        try {
            if (!empty($validatedSettingsInput)) {
                foreach ($validatedSettingsInput as $key => $value) {
                    if (in_array($key, $this->aboutPageSettingKeys)) {
                        Setting::updateOrCreate(['key' => $key], ['value' => $value ?? '']);
                    }
                }
            }
            DB::commit();
            Cache::forget('site_global_settings');
            Alert::success('Berhasil!', 'Konten halaman "Tentang Kami" berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan pengaturan konten Tentang Kami: ' . $e->getMessage(), ['exception' => $e]);
            Alert::error('Gagal!', 'Terjadi kesalahan saat menyimpan konten halaman "Tentang Kami".');
        }

        return redirect()->route('admin.settings.about.index');
    }
}
