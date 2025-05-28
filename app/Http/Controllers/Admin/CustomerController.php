<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Model User
use Illuminate\Http\Request;
// Anda tidak perlu import Role atau Permission di sini kecuali Anda melakukan query spesifik terkait itu.
// Pengecekan role akan dilakukan pada instance User.

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ubah cara filter pelanggan: tampilkan pengguna yang TIDAK memiliki role 'admin'
        // atau yang memiliki role 'pelanggan'
        $query = User::query();

        // Opsi 1: Tampilkan semua user yang BUKAN admin
        $query->whereDoesntHave('roles', function ($q) {
            $q->where('name', 'admin');
        });

        // Opsi 2: Atau, jika Anda ingin lebih eksplisit hanya menampilkan yang memiliki role 'pelanggan'
        // $query->role('pelanggan'); // Ini adalah scope yang disediakan oleh Spatie

        $query->orderBy('created_at', 'desc');

        // Fitur Pencarian (tetap sama)
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm);
            });
        }

        $customers = $query->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $customer) // Route model binding tetap menggunakan User
    {
        // Pastikan kita tidak menampilkan detail admin atau user lain yang bukan 'pelanggan'
        // (tergantung definisi "pelanggan" Anda)
        if ($customer->hasRole('admin')) {
            // Atau jika Anda ingin memastikan hanya role 'pelanggan' yang ditampilkan:
            // if (!$customer->hasRole('pelanggan')) {
            return redirect()->route('admin.customers.index')->with('error', 'Pengguna yang diminta bukan pelanggan.');
        }

        // Eager load pesanan pelanggan (tetap sama)
        $customer->load(['orders' => function ($query) {
            $query->orderBy('created_at', 'desc')->withCount('orderItems');
        }]);

        return view('admin.customers.show', compact('customer'));
    }
}
