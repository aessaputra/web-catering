<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuCategory;
use Illuminate\Support\Str;

class MenuCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Nasi Kotak', 'description' => 'Berbagai pilihan nasi kotak untuk acara Anda.'],
            ['name' => 'Kue Tradisional', 'description' => 'Aneka kue basah dan kering tradisional.'],
            ['name' => 'Snack Box', 'description' => 'Pilihan snack box untuk rapat dan acara lainnya.'],
            ['name' => 'Minuman', 'description' => 'Berbagai minuman segar dan hangat.'],
        ];

        foreach ($categories as $category) {
            MenuCategory::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                ]
            );
        }
    }
}
