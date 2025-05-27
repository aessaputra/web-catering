<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\MenuItem;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'selesai')->sum('total_amount');
        $newCustomers = User::where('is_admin', false)->whereDate('created_at', today())->count();
        $totalMenuItems = MenuItem::count();

        return view('admin.dashboard', compact('totalOrders', 'totalRevenue', 'newCustomers', 'totalMenuItems'));
    }
}