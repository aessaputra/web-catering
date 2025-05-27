<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StoreOrderRequest;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        // Ambil semua kategori beserta item menunya
        $categories = MenuCategory::with(['menuItems' => function ($query) {
            // $query->where('is_available', true); // Jika ada status ketersediaan item
        }])->orderBy('name', 'asc')->get();

        // Alternatif jika ingin flat list dan di-group di view dengan JS, atau jika item sedikit
        // $menuItems = MenuItem::orderBy('name', 'asc')->get();

        return view('public.order_form', compact('categories'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $validatedData = $request->validated();

        DB::beginTransaction(); // Mulai transaksi database

        try {
            $totalAmount = 0;
            $orderItemsData = [];

            foreach ($validatedData['items'] as $itemInput) {
                if (empty($itemInput['id']) || empty($itemInput['quantity']) || $itemInput['quantity'] < 1) {
                    continue; // Lewati item yang tidak valid atau quantity 0
                }

                $menuItem = MenuItem::findOrFail($itemInput['id']);
                $subTotal = $menuItem->price * $itemInput['quantity'];
                $totalAmount += $subTotal;

                $orderItemsData[] = [
                    'menu_item_id' => $menuItem->id,
                    'quantity'     => $itemInput['quantity'],
                    'price'        => $menuItem->price, // Simpan harga saat pemesanan
                    'sub_total'    => $subTotal,
                ];
            }

            if (empty($orderItemsData)) {
                return back()->withInput()->with('error', 'Tidak ada item yang dipilih atau jumlah tidak valid.');
            }

            $order = Order::create([
                'user_id'          => Auth::id(), // Akan null jika guest
                'customer_name'    => Auth::check() ? Auth::user()->name : $validatedData['customer_name'],
                'customer_email'   => Auth::check() ? Auth::user()->email : $validatedData['customer_email'],
                'customer_phone'   => Auth::check() ? (Auth::user()->phone ?? $validatedData['customer_phone']) : $validatedData['customer_phone'], // Asumsi ada field 'phone' di model User
                'delivery_address' => $validatedData['delivery_address'],
                'event_date'       => $validatedData['event_date'],
                'notes'            => $validatedData['notes'] ?? null,
                'total_amount'     => $totalAmount,
                'status'           => 'pending', // Status awal pesanan
            ]);

            // Buat OrderItems
            foreach ($orderItemsData as $itemData) {
                $order->orderItems()->create($itemData);
            }

            DB::commit(); // Konfirmasi transaksi jika semua berjalan lancar

            // Kirim notifikasi email ke pelanggan dan admin (akan diimplementasikan nanti)
            // Mail::to($order->customer_email)->send(new OrderPlaced($order));
            // Mail::to(config('mail.admin_address'))->send(new NewOrderNotification($order));

            Log::info('Pesanan baru berhasil dibuat:', ['order_id' => $order->id, 'total' => $totalAmount]);

            // Redirect ke halaman terima kasih atau detail pesanan
            return redirect()->route('home')->with('success', 'Pesanan Anda telah berhasil dibuat! Nomor Pesanan: ' . $order->id . '. Kami akan segera menghubungi Anda.');
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan transaksi jika terjadi error
            Log::error('Gagal membuat pesanan: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memproses pesanan Anda. Silakan coba lagi.');
        }
    }
}
