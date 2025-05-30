<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected $orderStatuses = [
        'pending' => 'Pending',
        'processing' => 'Diproses',
        'shipped' => 'Dikirim',
        'delivered' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    public function index(Request $request)
    {
        $query = Order::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15)->withQueryString();
        return view('admin.orders.index', compact('orders'), ['orderStatuses' => $this->orderStatuses]);
    }

    public function show(Order $order)
    {
        if ($order->trashed()) {
            Alert::warning('Diarsipkan', 'Pesanan ini telah diarsipkan.');
        }
        $order->load('orderItems.menuItem', 'user');
        return view('admin.orders.show', compact('order'), ['orderStatuses' => $this->orderStatuses]);
    }

    public function update(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:' . implode(',', array_keys($this->orderStatuses))]);
        $order->update(['status' => $request->status]);
        Alert::success('Status Diperbarui!', "Status Pesanan #{$order->id} berhasil diubah menjadi '{$this->orderStatuses[$request->status]}'.");
        return redirect()->route('admin.orders.show', $order);
    }

    /**
     * Soft delete the specified order.
     */
    public function destroy(Order $order)
    {
        try {
            $orderId = $order->id;
            $order->delete();
            Alert::success('Berhasil Diarsipkan!', "Pesanan #{$orderId} telah berhasil diarsipkan.");
        } catch (\Exception $e) {
            Log::error('Gagal mengarsipkan pesanan: ' . $e->getMessage(), ['order_id' => $order->id, 'exception' => $e]);
            Alert::error('Gagal!', 'Terjadi kesalahan saat mencoba mengarsipkan pesanan.');
        }
        return redirect()->route('admin.orders.index');
    }

    /**
     * Display a listing of archived orders.
     */
    public function archived(Request $request)
    {
        $query = Order::onlyTrashed()->with('user')->orderBy('deleted_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $archivedOrders = $query->paginate(15)->withQueryString();
        return view('admin.orders.archived', compact('archivedOrders'), ['orderStatuses' => $this->orderStatuses]);
    }

    /**
     * Restore a soft-deleted order.
     */
    public function restore($orderId)
    {
        $order = Order::onlyTrashed()->findOrFail($orderId);
        try {
            $order->restore();
            Alert::success('Berhasil Dipulihkan!', "Pesanan #{$order->id} telah berhasil dipulihkan.");
        } catch (\Exception $e) {
            Log::error('Gagal memulihkan pesanan: ' . $e->getMessage(), ['order_id' => $orderId, 'exception' => $e]);
            Alert::error('Gagal!', 'Terjadi kesalahan saat mencoba memulihkan pesanan.');
            return redirect()->route('admin.orders.archived');
        }
        return redirect()->route('admin.orders.archived');
    }

    /**
     * Permanently delete a soft-deleted order.
     */
    public function forceDelete($orderId)
    {
        $order = Order::onlyTrashed()->findOrFail($orderId);
        DB::beginTransaction();
        try {
            $orderIdBackup = $order->id;
            $order->forceDelete();
            DB::commit();
            Alert::success('Berhasil Dihapus Permanen!', "Pesanan #{$orderIdBackup} dan item terkait telah dihapus secara permanen.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus permanen pesanan: ' . $e->getMessage(), ['order_id' => $orderId, 'exception' => $e]);
            Alert::error('Gagal!', 'Terjadi kesalahan saat mencoba menghapus pesanan secara permanen.');
            return redirect()->route('admin.orders.archived');
        }
        return redirect()->route('admin.orders.archived');
    }
}
