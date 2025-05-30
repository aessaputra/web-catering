<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->whereDoesntHave('roles', function ($q) {
            $q->where('name', 'admin');
        });
        // Secara default User::query() tidak akan mengambil yang di-soft delete

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm);
            });
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        if ($customer->trashed()) {
            Alert::warning('Diarsipkan', 'Pelanggan ini telah diarsipkan.');
            // Anda mungkin ingin mengarahkan ke halaman arsip atau tetap di sini dengan indikasi
            // Untuk sekarang, kita biarkan bisa dilihat tapi dengan status.
            // Atau redirect ke daftar pelanggan aktif
            // return redirect()->route('admin.customers.index');
        }
        if ($customer->hasRole('admin') && !$customer->trashed()) { // Admin aktif tidak boleh dilihat di sini
            Alert::error('Akses Ditolak', 'Pengguna yang diminta bukan pelanggan.');
            return redirect()->route('admin.customers.index');
        }

        $customer->load(['orders' => function ($query) {
            $query->orderBy('created_at', 'desc')->withCount('orderItems');
        }]);

        return view('admin.customers.show', compact('customer'));
    }

    public function destroy(User $customer) // Ini akan menjadi Soft Delete
    {
        if ($customer->hasRole('admin')) {
            Alert::error('Gagal!', 'Akun admin tidak dapat diarsipkan melalui halaman ini.');
            return redirect()->route('admin.customers.index');
        }
        if (Auth::id() === $customer->id) {
            Alert::error('Gagal!', 'Anda tidak dapat mengarsipkan akun Anda sendiri.');
            return redirect()->route('admin.customers.index');
        }

        try {
            $customerName = $customer->name;
            $customer->delete(); // Melakukan SOFT DELETE
            Alert::success('Berhasil Diarsipkan!', "Pelanggan '{$customerName}' telah berhasil diarsipkan.");
        } catch (\Exception $e) {
            Log::error('Gagal mengarsipkan pelanggan: ' . $e->getMessage(), ['customer_id' => $customer->id, 'exception' => $e]);
            Alert::error('Gagal!', 'Terjadi kesalahan saat mencoba mengarsipkan pelanggan.');
        }
        return redirect()->route('admin.customers.index');
    }

    /**
     * Display a listing of the archived customers.
     */
    public function archived(Request $request)
    {
        $query = User::onlyTrashed()->whereDoesntHave('roles', function ($q) {
            $q->where('name', 'admin');
        });

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm);
            });
        }
        $archivedCustomers = $query->orderBy('deleted_at', 'desc')->paginate(15)->withQueryString();
        return view('admin.customers.archived', compact('archivedCustomers'));
    }

    /**
     * Restore the specified soft-deleted customer.
     */
    public function restore($customerId) // Tidak menggunakan Route Model Binding agar bisa findOrFail withTrashed
    {
        $customer = User::onlyTrashed()->whereDoesntHave('roles', function ($q) {
            $q->where('name', 'admin');
        })->findOrFail($customerId);

        try {
            $customer->restore();
            Alert::success('Berhasil Dipulihkan!', "Pelanggan '{$customer->name}' telah berhasil dipulihkan.");
        } catch (\Exception $e) {
            Log::error('Gagal memulihkan pelanggan: ' . $e->getMessage(), ['customer_id' => $customerId, 'exception' => $e]);
            Alert::error('Gagal!', 'Terjadi kesalahan saat mencoba memulihkan pelanggan.');
            return redirect()->route('admin.customers.archived');
        }
        return redirect()->route('admin.customers.archived');
    }

    /**
     * Permanently delete the specified soft-deleted customer.
     */
    public function forceDelete($customerId)
    {
        $customer = User::onlyTrashed()->whereDoesntHave('roles', function ($q) {
            $q->where('name', 'admin');
        })->findOrFail($customerId);

        DB::beginTransaction();
        try {
            $customerName = $customer->name;
            $customer->forceDelete();

            DB::commit();

            Alert::success('Berhasil Dihapus Permanen!', "Pelanggan '{$customerName}' telah dihapus secara permanen. Riwayat pesanan mereka (jika ada) tetap tersimpan tanpa terhubung langsung ke pelanggan ini.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus permanen pelanggan: ' . $e->getMessage(), ['customer_id' => $customerId, 'exception' => $e]);
            Alert::error('Gagal!', 'Terjadi kesalahan saat mencoba menghapus pelanggan secara permanen. Periksa log untuk detail.');
            return redirect()->route('admin.customers.archived');
        }
        return redirect()->route('admin.customers.archived');
    }
}
