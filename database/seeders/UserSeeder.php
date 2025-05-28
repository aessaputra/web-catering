<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@kulinermamah.biz.id'], // Gunakan email yang konsisten dengan RolesAndPermissionsSeeder
            [
                'name' => 'Admin Catering', // Nama bisa disesuaikan
                'password' => Hash::make('password'), // Ganti dengan password yang aman
                'email_verified_at' => now(),
                'phone' => '081234567890', // Contoh jika ada field phone
                // 'is_admin' => true, // DIHAPUS atau DIKOMENTARI
            ]
        );

        User::updateOrCreate(
            ['email' => 'aes@pembeli.com'], // Gunakan email yang konsisten atau ganti
            [
                'name' => 'Aes Pembeli', // Nama bisa disesuaikan
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'phone' => '089876543210', // Contoh jika ada field phone
                // 'is_admin' => false, // DIHAPUS atau DIKOMENTARI
            ]
        );

        // Jika Anda menggunakan email 'aessaputra@yahoo.com' sebagai pelanggan di RolesAndPermissionsSeeder,
        // pastikan email di sini juga sesuai, atau buat entri baru.
        // Contoh:
        // User::updateOrCreate(
        //     ['email' => 'aessaputra@yahoo.com'],
        //     [
        //         'name' => 'Aes Saputra',
        //         'password' => Hash::make('password'),
        //         'email_verified_at' => now(),
        //         'phone' => '08xxxxxxxxxx',
        //     ]
        // );
    }
}
