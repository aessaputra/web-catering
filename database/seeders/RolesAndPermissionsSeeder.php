<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // --- PERMISSIONS ---
        // Untuk Admin Panel Umum
        Permission::updateOrCreate(['name' => 'akses admin panel', 'guard_name' => 'web']);

        // Manajemen Menu
        Permission::updateOrCreate(['name' => 'lihat kategori menu', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'buat kategori menu', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'edit kategori menu', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'hapus kategori menu', 'guard_name' => 'web']);

        Permission::updateOrCreate(['name' => 'lihat item menu', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'buat item menu', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'edit item menu', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'hapus item menu', 'guard_name' => 'web']);

        // Manajemen Pesanan
        Permission::updateOrCreate(['name' => 'lihat semua pesanan', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'lihat detail pesanan', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'update status pesanan', 'guard_name' => 'web']);

        // Manajemen Pelanggan
        Permission::updateOrCreate(['name' => 'lihat daftar pelanggan', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'lihat detail pelanggan', 'guard_name' => 'web']);

        // Manajemen Pengaturan
        Permission::updateOrCreate(['name' => 'kelola pengaturan website', 'guard_name' => 'web']);

        // --- ROLES ---
        // Role Admin
        $adminRole = Role::updateOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        // Berikan semua permission yang sudah dibuat ke role admin
        // Cara mudah: $adminRole->givePermissionTo(Permission::all());
        // Atau satu per satu jika lebih terkontrol:
        $adminRole->givePermissionTo([
            'akses admin panel',
            'lihat kategori menu',
            'buat kategori menu',
            'edit kategori menu',
            'hapus kategori menu',
            'lihat item menu',
            'buat item menu',
            'edit item menu',
            'hapus item menu',
            'lihat semua pesanan',
            'lihat detail pesanan',
            'update status pesanan',
            'lihat daftar pelanggan',
            'lihat detail pelanggan',
            'kelola pengaturan website',
        ]);

        // Role Pelanggan
        $customerRole = Role::updateOrCreate(['name' => 'pelanggan', 'guard_name' => 'web']);
        // Pelanggan mungkin tidak memiliki permission spesifik di level ini,
        // atau bisa memiliki permission seperti 'buat pesanan', 'lihat riwayat pesanan pribadi'
        // Untuk saat ini, kita biarkan kosong atau beri permission dasar jika perlu

        // --- TUGASKAN ROLE KE USER YANG SUDAH ADA ---
        // Contoh: User admin yang sudah Anda buat di UserSeeder
        $adminUser = User::where('email', 'admin@kulinermamah.biz.id')->first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
            // Jika Anda sudah tidak menggunakan kolom is_admin, Anda bisa mengomentari baris update is_admin di UserSeeder
        }

        // Contoh: User pelanggan yang sudah Anda buat di UserSeeder
        $customerUser = User::where('email', 'aes@pembeli.com')->first();
        if ($customerUser) {
            $customerUser->assignRole('pelanggan');
        }

        // Jika Anda memiliki banyak user pelanggan, Anda bisa loop dan assign role 'pelanggan'
        // User::where('is_admin', false)->get()->each(function($user) {
        //     $user->assignRole('pelanggan');
        // });
    }
}
