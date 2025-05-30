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
            ['key' => 'site_logo', 'value' => ''],
            ['key' => 'hero_image_homepage', 'value' => ''],
            ['key' => 'operating_hours', 'value' => "Senin - Minggu: 08:00 - 23:00 WIB"],
            ['key' => 'contact_email', 'value' => 'info@cateringlezat.com'],
            ['key' => 'contact_whatsapp', 'value' => '0812-3456-7890'],
            ['key' => 'address', 'value' => 'Jl. Kuliner No. 1, Kota Enak, Indonesia'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/cateringlezat'],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/cateringlezat'],
            ['key' => 'Maps_url', 'value' => 'URL_EMBED_Maps_ANDA'],
            ['key' => 'about_hero_title', 'value' => 'Mengenal Lebih Dekat'],
            ['key' => 'about_hero_subtitle_template', 'value' => '{appName}'],
            ['key' => 'about_history_title', 'value' => 'Perjalanan Kami'],
            ['key' => 'about_history_content', 'value' => "Selamat datang di {appName}! Kami memulai perjalanan kuliner kami pada tahun [TAHUN BERDIRI ANDA] dengan misi sederhana: menyajikan makanan berkualitas tinggi dengan cita rasa otentik untuk setiap acara spesial Anda. Berawal dari semangat dan kecintaan terhadap dunia masak, dari dapur rumahan yang penuh kehangatan, kami telah bertumbuh dan berkembang.\n\nKini, kami bangga telah menjadi penyedia layanan catering terpercaya yang melayani beragam kebutuhan, mulai dari pertemuan keluarga yang intim, acara syukuran, arisan, hingga perayaan besar dan event korporat."],
            ['key' => 'about_vision_title', 'value' => 'Visi Kami'],
            ['key' => 'about_vision_content', 'value' => 'Menjadi pilihan utama layanan catering yang dikenal karena kualitas cita rasa, inovasi kuliner, dan pelayanan yang tak terlupakan di [KOTA/WILAYAH ANDA] dan sekitarnya.'],
            ['key' => 'about_mission_title', 'value' => 'Misi Kami'],
            ['key' => 'about_mission_point_1', 'value' => 'Menyajikan hidangan lezat, sehat, dan higienis menggunakan bahan-bahan segar berkualitas terbaik yang dipilih dengan cermat.'],
            ['key' => 'about_mission_point_2', 'value' => 'Memberikan pelayanan yang ramah, profesional, responsif, dan solutif terhadap setiap kebutuhan unik pelanggan.'],
            ['key' => 'about_mission_point_3', 'value' => 'Terus berinovasi dalam variasi menu dan konsep penyajian untuk memberikan pengalaman kuliner yang segar dan mengesankan.'],
            ['key' => 'about_mission_point_4', 'value' => 'Membangun hubungan jangka panjang yang saling menguntungkan dengan pelanggan, mitra pemasok, dan seluruh tim kami.'],

        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}
