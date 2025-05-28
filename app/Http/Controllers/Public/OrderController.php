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
use RealRashid\SweetAlert\Facades\Alert;

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

        DB::beginTransaction();

        try {
            $totalAmount = 0;
            $orderItemsData = [];

            foreach ($validatedData['items'] as $itemInput) {
                if (empty($itemInput['id']) || !isset($itemInput['quantity']) || $itemInput['quantity'] < 1) {
                    continue;
                }
                $menuItem = MenuItem::findOrFail($itemInput['id']);
                $subTotal = $menuItem->price * $itemInput['quantity'];
                $totalAmount += $subTotal;
                $orderItemsData[] = [
                    'menu_item_id' => $menuItem->id,
                    'quantity'     => $itemInput['quantity'],
                    'price'        => $menuItem->price,
                    'sub_total'    => $subTotal,
                ];
            }

            if (empty($orderItemsData)) {
                DB::rollBack();
                return back()->withInput()->with('error', 'Tidak ada item yang dipilih atau jumlah tidak valid.');
            }

            $customerPhone = null; // Default ke null
            if (Auth::check()) {
                $customerPhone = Auth::user()->phone; // Ambil dari user yang login
                // Jika Anda ingin user yang login bisa override phone-nya via form (misalnya formnya tidak menyembunyikan field ini),
                // Anda bisa tambahkan:
                // if (empty($customerPhone) && isset($validatedData['customer_phone'])) {
                //     $customerPhone = $validatedData['customer_phone'];
                // }
            } else {
                // Hanya ambil dari $validatedData jika guest dan field itu ada (seharusnya ada karena 'required' untuk guest)
                $customerPhone = $validatedData['customer_phone'] ?? null;
            }

            $order = Order::create([
                'user_id'          => Auth::id(),
                'customer_name'    => Auth::check() ? Auth::user()->name : $validatedData['customer_name'],
                'customer_email'   => Auth::check() ? Auth::user()->email : $validatedData['customer_email'],
                'customer_phone'   => $customerPhone, // Gunakan variabel $customerPhone yang sudah diproses
                'delivery_address' => $validatedData['delivery_address'],
                'event_date'       => $validatedData['event_date'],
                'notes'            => $validatedData['notes'] ?? null,
                'total_amount'     => $totalAmount,
                'status'           => 'pending',
            ]);

            foreach ($orderItemsData as $itemData) {
                $order->orderItems()->create($itemData);
            }

            DB::commit();

            Log::info('Pesanan baru berhasil dibuat:', ['order_id' => $order->id, 'total' => $totalAmount]);
            Alert::success('Berhasil!', 'Pesanan Anda telah berhasil dibuat! Nomor Pesanan: ' . $order->id . '. Kami akan segera menghubungi Anda.');
            return redirect()->route('home');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal membuat pesanan: ' . $e->getMessage(), ['exception' => $e]); // Ubah 'trace' menjadi 'exception' untuk logging standar
            // Jangan tampilkan pesan error 'Terjadi kesalahan...' jika ini adalah ValidationException, biarkan Laravel yang handle
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                throw $e;
            }
            Alert::error('Gagal!', 'Terjadi kesalahan saat memproses pesanan Anda. Silakan coba lagi.');
            return back()->withInput();
        }
    }
}
