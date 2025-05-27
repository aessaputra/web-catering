<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::where('is_admin', false) // Hanya tampilkan pengguna yang bukan admin
            ->orderBy('created_at', 'desc');

        // Fitur Pencarian (opsional)
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
    public function show(User $customer) // Menggunakan Route Model Binding, pastikan parameter di route adalah 'customer'
    {
        // Pastikan kita tidak menampilkan detail admin jika ada yang mencoba akses via URL
        if ($customer->is_admin) {
            // abort(404); // Atau redirect dengan pesan error
            return redirect()->route('admin.customers.index')->with('error', 'Pengguna yang diminta bukan pelanggan.');
        }

        // Eager load pesanan pelanggan, diurutkan dari yang terbaru
        $customer->load(['orders' => function ($query) {
            $query->orderBy('created_at', 'desc')->withCount('orderItems');
        }]);

        return view('admin.customers.show', compact('customer'));
    }

    // Untuk manajemen pelanggan, biasanya admin tidak melakukan Create, Update, Delete data user secara langsung dari sini
    // karena registrasi dilakukan oleh pengguna, dan update profil dilakukan oleh pengguna sendiri.
    // Penghapusan user mungkin memerlukan pertimbangan khusus (soft delete, anonymize data pesanan, dll.)
    // Jadi, kita hanya fokus pada index dan show sesuai definisi rute.
}
