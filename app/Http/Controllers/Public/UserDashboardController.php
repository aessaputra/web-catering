<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class UserDashboardController extends Controller
{
    /**
     * Display the customer's dashboard, focusing on order history.
     */
    public function index()
    {
        $user = Auth::user();

        // Jika pengguna yang login adalah admin, arahkan ke dashboard admin
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        // Jika bukan admin, lanjutkan untuk menampilkan dashboard pelanggan
        $orders = Order::where('user_id', $user->id)
            ->withCount('orderItems')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $orderStatuses = [
            'pending' => 'Pending',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'delivered' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        // Pastikan view 'public.dashboard.index' sudah ada
        return view('public.dashboard.index', compact('user', 'orders', 'orderStatuses'));
    }

    public function showOrder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan melihat pesanan ini.');
        }
        $order->load(['orderItems.menuItem']);
        $orderStatuses = [ /* ... status ... */]; // Anda bisa mendefinisikannya di sini atau mengambil dari properti class
        return view('public.dashboard.order_detail', compact('order', 'orderStatuses'));
    }
}
