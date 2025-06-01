<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StoreOrderRequest;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Setting; // Digunakan untuk fallback nama aplikasi jika perlu
// OrderItem model tidak perlu di-import di sini jika kita menggunakan createMany dari relasi Order
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;

// Import Midtrans
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap as MidtransSnap;
use Midtrans\ApiRequestor as MidtransApiRequestor; // Untuk error handling lebih baik

class OrderController extends Controller
{
    /**
     * Set Midtrans configuration.
     */
    public function __construct()
    {
        MidtransConfig::$serverKey = config('services.midtrans.server_key');
        MidtransConfig::$isProduction = config('services.midtrans.is_production', false); // Default false jika tidak ada di .env
        MidtransConfig::$isSanitized = config('services.midtrans.is_sanitized', true);   // Default true
        MidtransConfig::$is3ds = config('services.midtrans.is_3ds', true);          // Default true jika kartu kredit
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        $categories = MenuCategory::with(['menuItems' => function ($query) {
            // Anda bisa menambahkan filter di sini jika item menu memiliki status ketersediaan
            // Contoh: $query->where('is_available', true)->orderBy('name', 'asc');
            $query->orderBy('name', 'asc');
        }])->whereHas('menuItems') // Hanya kategori yang memiliki item menu
            ->orderBy('name', 'asc')
            ->get();

        // Jika tidak ada kategori atau item sama sekali, mungkin beri pesan
        if ($categories->isEmpty()) {
            Alert::info('Menu Kosong', 'Maaf, saat ini belum ada menu yang tersedia untuk dipesan.');
            // Anda bisa redirect ke halaman menu atau menampilkan view khusus
        }

        return view('public.order_form', compact('categories'));
    }

    /**
     * Store a newly created order in storage and initiate payment.
     */
    public function store(StoreOrderRequest $request)
    {
        $validatedData = $request->validated();

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            $orderItemsDataForModel = [];
            $itemDetailsForMidtrans = [];

            foreach ($validatedData['items'] as $itemInput) {
                // Skip jika tidak ada ID, atau kuantitas tidak ada/kurang dari 1
                if (empty($itemInput['id']) || !isset($itemInput['quantity']) || !filter_var($itemInput['quantity'], FILTER_VALIDATE_INT) || $itemInput['quantity'] < 1) {
                    continue;
                }

                $menuItem = MenuItem::find($itemInput['id']);
                if (!$menuItem) {
                    // Item tidak ditemukan, bisa jadi dilewati atau lempar error
                    Log::warning('MenuItem tidak ditemukan saat membuat pesanan:', ['item_id' => $itemInput['id']]);
                    continue;
                }

                $quantity = (int) $itemInput['quantity'];
                $subTotal = $menuItem->price * $quantity;
                $totalAmount += $subTotal;

                $orderItemsDataForModel[] = [
                    'menu_item_id' => $menuItem->id,
                    'quantity'     => $quantity,
                    'price'        => $menuItem->price,
                    'sub_total'    => $subTotal,
                ];
                $itemDetailsForMidtrans[] = [
                    'id'       => (string) $menuItem->id, // Midtrans biasanya suka string
                    'price'    => (int) round($menuItem->price), // Pastikan integer, bulatkan jika float
                    'quantity' => $quantity,
                    'name'     => Str::limit($menuItem->name, 48, '') // Midtrans punya batasan panjang nama, hapus '...' jika terlalu panjang
                ];
            }

            if (empty($orderItemsDataForModel)) {
                DB::rollBack();
                Alert::error('Pesanan Kosong', 'Tidak ada item yang dipilih atau jumlah tidak valid. Silakan pilih minimal satu item.');
                return back()->withInput()->withErrors(['items_overall' => 'Anda harus memesan setidaknya satu item.']);
            }

            $customerPhone = Auth::check() ? (Auth::user()->phone ?? $validatedData['customer_phone'] ?? null) : ($validatedData['customer_phone'] ?? null);
            $customerName = Auth::check() ? Auth::user()->name : $validatedData['customer_name'];
            $customerEmail = Auth::check() ? Auth::user()->email : $validatedData['customer_email'];

            $order = Order::create([
                'user_id'          => Auth::id(), // Akan null jika guest
                'customer_name'    => $customerName,
                'customer_email'   => $customerEmail,
                'customer_phone'   => $customerPhone,
                'delivery_address' => $validatedData['delivery_address'],
                'event_date'       => $validatedData['event_date'],
                'notes'            => $validatedData['notes'] ?? null,
                'total_amount'     => $totalAmount,
                'status'           => 'pending_payment', // Status order aplikasi
                'payment_status'   => 'pending',         // Status pembayaran Midtrans
            ]);

            $order->orderItems()->createMany($orderItemsDataForModel);

            // Buat transaksi Midtrans
            $midtransOrderId = $order->id . '-' . Str::upper(Str::random(5)); // ID unik untuk Midtrans
            $midtransParams = [
                'transaction_details' => [
                    'order_id' => $midtransOrderId,
                    'gross_amount' => (int) round($order->total_amount), // Pastikan integer
                ],
                'customer_details' => [
                    'first_name' => Str::limit($customerName, 20, ''), // Midtrans batasan nama
                    // 'last_name' => '', // Opsional
                    'email' => $customerEmail,
                    'phone' => preg_replace('/[^0-9]/', '', $customerPhone), // Hanya angka untuk telepon
                    'billing_address' => [
                        'address' => Str::limit($order->delivery_address, 60, ''),
                        // 'city' => 'Nama Kota', // Sebaiknya ada field terpisah
                        // 'postal_code' => 'Kode Pos', // Sebaiknya ada field terpisah
                        // 'country_code' => 'IDN'
                    ],
                    // 'shipping_address' => [ /* ... */ ], // Opsional
                ],
                'item_details' => $itemDetailsForMidtrans,
                'expiry' => [
                    'start_time' => now()->format('Y-m-d H:i:s O'), // YYYY-MM-DD HH:MM:SS +ZZOO
                    'unit' => 'minutes',
                    'duration' => config('services.midtrans.expiry_duration', 1440) // Ambil dari config, default 24 jam
                ],
                // Anda bisa menambahkan callback URL di dashboard Midtrans
            ];

            Log::info('Midtrans Snap Request Params for Order ID ' . $order->id . ' (Midtrans Order ID ' . $midtransOrderId . '):', $midtransParams);

            $snapToken = MidtransSnap::getSnapToken($midtransParams);

            $order->payment_token = $snapToken; // Simpan token Snap ke order
            $order->midtrans_order_id = $midtransOrderId; // Simpan juga ID Order Midtrans (buat kolom baru jika perlu)
            $order->save();

            DB::commit();

            Log::info('Pesanan baru berhasil dibuat dan Snap Token diterima:', ['order_id' => $order->id, 'midtrans_order_id' => $midtransOrderId, 'snap_token' => $snapToken]);

            // Arahkan ke halaman checkout internal yang akan memuat Snap.js
            return redirect()->route('payment.checkout', ['orderId' => $order->id]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::warning('Validasi gagal saat membuat pesanan:', $e->errors());
            // Biarkan Laravel menangani redirect back with errors untuk ValidationException
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Midtrans Snap API Exception: ' . $e->getMessage(), [
                'order_data_attempted' => $validatedData,
                'midtrans_params_sent' => $midtransParams ?? null // Kirim parameter jika sudah dibuat
            ]);
            Alert::error('Gagal Membuat Pembayaran', 'Tidak dapat terhubung ke gateway pembayaran saat ini. Silakan coba beberapa saat lagi atau hubungi kami.');
            return back()->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal membuat pesanan atau transaksi Midtrans: ' . $e->getMessage(), [
                'exception_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString() // Hati-hati dengan trace di produksi
            ]);
            Alert::error('Gagal Proses Pesanan!', 'Terjadi kesalahan internal saat memproses pesanan Anda. Silakan coba lagi atau hubungi kami jika masalah berlanjut.');
            return back()->withInput();
        }
    }

    /**
     * Menampilkan halaman untuk memproses pembayaran dengan Midtrans Snap.
     */
    public function showPaymentPage($orderId)
    {
        $order = Order::with('user')->findOrFail($orderId);

        // Pengecekan keamanan tambahan (opsional, tapi baik)
        // Ganti pengecekan admin sesuai atribut pada model User, misal 'is_admin'
        if (Auth::check() && Auth::id() !== $order->user_id && !(Auth::user()->is_admin ?? false)) {
            // Jika user login, pastikan itu user yang memesan atau admin
            Alert::error('Akses Ditolak', 'Anda tidak berhak mengakses halaman pembayaran ini.');
            return redirect()->route('home');
        }
        // Jika guest, kita asumsikan session atau cara lain (jika ada) untuk membatasi akses
        // Untuk sekarang, kita izinkan jika order ditemukan by ID

        if (empty($order->payment_token)) {
            Log::warning('Token pembayaran Midtrans tidak ditemukan untuk Order ID: ' . $order->id);
            Alert::error('Token Hilang', 'Token pembayaran untuk pesanan ini tidak valid atau tidak ditemukan. Silakan coba buat pesanan lagi.');
            return redirect()->route('dashboard.orders.show', $order->id); // atau ke form order
        }

        if (in_array($order->payment_status, ['paid', 'settlement'])) {
            Alert::info('Sudah Lunas', 'Pesanan ini #' . $order->id . ' sudah berhasil dibayar.');
            return redirect()->route('dashboard.orders.show', $order->id);
        }

        // Jika payment_status 'failed', 'expired', 'cancelled', mungkin beri opsi buat token baru atau hubungi CS
        if (in_array($order->payment_status, ['failed', 'expired', 'cancelled'])) {
            Alert::warning('Pembayaran Tidak Berhasil', 'Pembayaran untuk pesanan ini sebelumnya ' . $order->payment_status . '. Harap buat pesanan baru atau hubungi kami.');
            return redirect()->route('order.create');
        }


        $clientKey = config('services.midtrans.client_key');
        $isProduction = config('services.midtrans.is_production');

        return view('public.payment.checkout', compact('order', 'clientKey', 'isProduction'));
    }
}
