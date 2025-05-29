<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Catering Lezat'],
            ['key' => 'site_description', 'value' => 'Solusi catering terbaik untuk segala acara Anda.'],
            ['key' => 'contact_email', 'value' => 'info@cateringlezat.com'],
            ['key' => 'contact_whatsapp', 'value' => '0812-3456-7890'],
            ['key' => 'address', 'value' => 'Jl. Kuliner No. 1, Kota Enak, Indonesia'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/cateringlezat'],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/cateringlezat'],
            ['key' => 'Maps_url', 'value' => 'URL_EMBED_Maps_ANDA'],
            ['key' => 'site_logo', 'value' => ''],
            ['key' => 'hero_image_homepage', 'value' => ''],

        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}
