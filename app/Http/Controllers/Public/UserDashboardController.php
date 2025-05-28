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

        // Ambil pesanan milik user yang sedang login, urutkan dari yang terbaru
        // Eager load orderItems untuk menghitung jumlah item atau menampilkan detail singkat jika perlu
        $orders = Order::where('user_id', $user->id)
            ->withCount('orderItems') // Menghitung jumlah item per pesanan
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Paginasi jika pesanannya banyak

        // Status pesanan untuk ditampilkan di view (sama seperti di Admin OrderController)
        $orderStatuses = [
            'pending' => 'Pending',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'delivered' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return view('public.dashboard.index', compact('user', 'orders', 'orderStatuses'));
    }

    /**
     * Display the specified order for the customer.
     */
    public function showOrder(Order $order)
    {
        // Pastikan pesanan ini milik user yang sedang login
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan melihat pesanan ini.');
        }

        $order->load(['orderItems.menuItem']); // Eager load detail item

        // Status pesanan
        $orderStatuses = [
            'pending' => 'Pending',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'delivered' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return view('public.dashboard.order_detail', compact('order', 'orderStatuses'));
    }
}
