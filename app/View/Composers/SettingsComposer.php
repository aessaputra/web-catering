<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SettingsComposer
{
  /**
   * Array dari setting keys yang ingin di-load secara global atau untuk view tertentu.
   * @var array
   */
  protected array $relevantSettingKeys = [
    'site_name',
    'site_description',
    'contact_email',
    'contact_whatsapp',
    'address',
    'instagram_url',
    'facebook_url',
    'Maps_url',
    'site_logo',
    'hero_image_homepage',
    'operating_hours',

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

  /**
   * Bind data to the view.
   *
   * @param  \Illuminate\View\View  $view
   * @return void
   */
  public function compose(View $view)
  {
    try {
      // Coba ambil settings dari cache dulu untuk performa
      // Cache akan mengingat data ini selama 60 menit (Anda bisa sesuaikan durasinya)
      $siteSettings = Cache::remember('site_global_settings', now()->addHour(), function () {
        $settingsFromDB = Setting::whereIn('key', $this->relevantSettingKeys)
          ->pluck('value', 'key');

        $allSettings = [];
        foreach ($this->relevantSettingKeys as $key) {
          $allSettings[$key] = $settingsFromDB->get($key, $this->getDefaultSettingValue($key));
        }
        return $allSettings;
      });

      $view->with('siteSettings', $siteSettings);
    } catch (\Exception $e) {
      Log::error('Error in SettingsComposer: ' . $e->getMessage());
      // Sediakan array kosong atau default jika ada error agar view tidak rusak
      $siteSettings = [];
      foreach ($this->relevantSettingKeys as $key) {
        $siteSettings[$key] = $this->getDefaultSettingValue($key);
      }
      $view->with('siteSettings', $siteSettings);
    }
  }

  /**
   * Menyediakan nilai default untuk settings jika tidak ditemukan di database.
   * @param string $key
   * @return string|null
   */
  private function getDefaultSettingValue(string $key): ?string
  {
    $defaults = [
      'site_name' => config('app.name', 'Catering Lezat'),
      'site_logo' => null,
      'hero_image_homepage' => null,
      'site_description' => 'Deskripsi default website catering Anda.',
      'contact_email' => 'email@default.com',
      'contact_whatsapp' => '000000000000',
      'address' => 'Alamat default.',
      'instagram_url' => '#',
      'facebook_url' => '#',
      'Maps_url' => '',
      'operating_hours' => "Senin - Minggu: 08:00 - 23:00 WIB",
      'about_hero_title' => 'Tentang Perusahaan Kami',
      'about_hero_subtitle_template' => 'Mengenal Lebih Dekat {appName}',
      'about_history_title' => 'Perjalanan Kami',
      'about_history_content' => "Tuliskan sejarah perusahaan Anda di sini. Anda bisa menggunakan placeholder {appName} yang akan diganti dengan nama situs Anda.",
      'about_vision_title' => 'Visi Kami',
      'about_vision_content' => 'Tuliskan visi perusahaan Anda di sini.',
      'about_mission_title' => 'Misi Kami',
      'about_mission_point_1' => 'Poin misi pertama.',
      'about_mission_point_2' => 'Poin misi kedua.',
      'about_mission_point_3' => 'Poin misi ketiga.',
      'about_mission_point_4' => 'Poin misi keempat.',
      'about_team_title' => 'Tim Profesional Kami',
      'about_team_content_1' => 'Deskripsikan tim Anda, paragraf pertama.',
      'about_team_content_2' => 'Deskripsikan tim Anda, paragraf kedua.',
    ];
    return $defaults[$key] ?? null;
  }
}
