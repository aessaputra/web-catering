<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class OrderController extends Controller
{
    // Daftar status pesanan yang valid
    private $orderStatuses = [
        'pending' => 'Pending',
        'processing' => 'Diproses',
        'shipped' => 'Dikirim',
        'delivered' => 'Selesai', // Atau 'Selesai'
        'cancelled' => 'Dibatalkan',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with('user') // Eager load relasi user jika ada
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status
        if ($request->filled('status') && array_key_exists($request->status, $this->orderStatuses)) {
            $query->where('status', $request->status);
        }

        // Pencarian berdasarkan nama pelanggan atau email atau ID pesanan
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('id', 'like', $searchTerm)
                    ->orWhere('customer_name', 'like', $searchTerm)
                    ->orWhere('customer_email', 'like', $searchTerm);
            });
        }

        $orders = $query->paginate(15)->withQueryString();
        $statuses = $this->orderStatuses;

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        // Eager load item pesanan beserta detail item menunya dan user (jika ada)
        $order->load(['orderItems.menuItem', 'user']);
        $statuses = $this->orderStatuses;
        return view('admin.orders.show', compact('order', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'string', \Illuminate\Validation\Rule::in(array_keys($this->orderStatuses))],
        ]);

        $order->status = $request->status;
        $order->save();

        // Kirim notifikasi ke pelanggan tentang perubahan status (opsional)
        // if ($order->user) {
        //     Mail::to($order->user->email)->send(new OrderStatusUpdated($order));
        // } else {
        //     Mail::to($order->customer_email)->send(new OrderStatusUpdated($order));
        // }

        Alert::success('Berhasil!', 'Status pesanan telah diperbarui.');

        return redirect()->route('admin.orders.show', $order);
    }

    // Method create, store, edit, destroy tidak kita gunakan untuk Order dari sisi admin
    // jadi bisa dihapus jika controllernya dibuat dengan --resource penuh.
    // Karena kita hanya menggunakan 'index', 'show', 'update' di routes,
    // maka method lain tidak akan terpanggil.
}
