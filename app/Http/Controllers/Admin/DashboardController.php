<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\MenuItem;
// Tidak perlu import Role secara spesifik di sini jika hanya menggunakan scope atau relasi

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'selesai')->sum('total_amount'); // Pastikan status 'selesai' ini konsisten

        // Menghitung pelanggan baru (user yang memiliki role 'pelanggan' dan terdaftar hari ini)
        // Opsi 1: Menggunakan scope 'role' dari Spatie
        $newCustomers = User::role('pelanggan') // Mengambil user dengan role 'pelanggan'
            ->whereDate('created_at', today())
            ->count();

        // Opsi 2: Alternatif jika Anda ingin user yang BUKAN admin (lebih mirip logika lama)
        // $newCustomers = User::whereDoesntHave('roles', function ($query) {
        //                         $query->where('name', 'admin');
        //                     })
        //                     ->whereDate('created_at', today())
        //                     ->count();
        // Pilih salah satu dari Opsi 1 atau Opsi 2 yang paling sesuai dengan definisi "pelanggan" Anda.
        // Opsi 1 lebih disarankan jika 'pelanggan' adalah role yang jelas.

        $totalMenuItems = MenuItem::count();

        return view('admin.dashboard', compact('totalOrders', 'totalRevenue', 'newCustomers', 'totalMenuItems'));
    }
}
