<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuItem;
use App\Models\MenuCategory;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nasiKotakCategory = MenuCategory::where('slug', 'nasi-kotak')->first();
        $kueTradisionalCategory = MenuCategory::where('slug', 'kue-tradisional')->first();
        $minumanCategory = MenuCategory::where('slug', 'minuman')->first();

        if ($nasiKotakCategory) {
            MenuItem::updateOrCreate(
                ['name' => 'Nasi Ayam Bakar', 'menu_category_id' => $nasiKotakCategory->id],
                [
                    'description' => 'Nasi putih, ayam bakar bumbu rempah, lalapan, sambal.',
                    'price' => 25000.00,
                    'image_path' => 'images/menu/nasi_ayam_bakar.jpg',
                    'is_featured' => true,
                ]
            );
            MenuItem::updateOrCreate(
                ['name' => 'Nasi Rendang Sapi', 'menu_category_id' => $nasiKotakCategory->id],
                [
                    'description' => 'Nasi putih, rendang sapi empuk, sayur nangka, sambal hijau.',
                    'price' => 35000.00,
                    'image_path' => 'images/menu/nasi_rendang.jpg',
                ]
            );
        }

        if ($kueTradisionalCategory) {
            MenuItem::updateOrCreate(
                ['name' => 'Lapis Legit', 'menu_category_id' => $kueTradisionalCategory->id],
                [
                    'description' => 'Kue lapis legit premium dengan rasa otentik.',
                    'price' => 15000.00,
                    'image_path' => 'images/menu/lapis_legit.jpg',
                    'is_featured' => true,
                ]
            );
            MenuItem::updateOrCreate(
                ['name' => 'Kue Lumpur', 'menu_category_id' => $kueTradisionalCategory->id],
                [
                    'description' => 'Kue lumpur pandan dengan kismis.',
                    'price' => 5000.00,
                    'image_path' => 'images/menu/kue_lumpur.jpg',
                ]
            );
        }

        if ($minumanCategory) {
            MenuItem::updateOrCreate(
                ['name' => 'Es Teh Manis', 'menu_category_id' => $minumanCategory->id],
                [
                    'description' => 'Es teh manis segar.',
                    'price' => 5000.00,
                    'image_path' => 'images/menu/es_teh_manis.jpg',
                ]
            );
            MenuItem::updateOrCreate(
                ['name' => 'Jus Jeruk', 'menu_category_id' => $minumanCategory->id],
                [
                    'description' => 'Jus jeruk peras asli.',
                    'price' => 10000.00,
                    'image_path' => 'images/menu/jus_jeruk.jpg',
                ]
            );
        }
    }
}
