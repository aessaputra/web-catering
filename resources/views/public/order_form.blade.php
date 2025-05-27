@extends('public.layouts.app')

@section('title', 'Form Pemesanan')

@push('styles')
    <style>
        /* Styling tambahan jika diperlukan */
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-12">🛒 Buat Pesanan Anda</h1>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p class="font-bold">Oops!</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p class="font-bold">Harap perbaiki kesalahan berikut:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('order.store') }}" method="POST" class="bg-white p-8 rounded-lg shadow-md"
            x-data="orderForm()">
            @csrf

            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-orange-600 mb-6 border-b pb-2">Pilih Menu</h2>
                @if ($categories->isEmpty())
                    <p class="text-gray-600">Maaf, belum ada menu yang tersedia untuk dipesan.</p>
                @else
                    @foreach ($categories as $category)
                        <div class="mb-6">
                            <h3 class="text-xl font-medium text-gray-700 mb-3">{{ $category->name }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach ($category->menuItems as $item)
                                    <div class="border p-4 rounded-lg shadow">
                                        <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : 'https://via.placeholder.com/150?text=' . urlencode($item->name) }}"
                                            alt="{{ $item->name }}" class="w-full h-32 object-cover rounded mb-3">
                                        <h4 class="font-semibold text-lg">{{ $item->name }}</h4>
                                        <p class="text-sm text-gray-600 mb-1">{{ Str::limit($item->description, 50) }}</p>
                                        <p class="text-orange-500 font-bold mb-2">Rp
                                            {{ number_format($item->price, 0, ',', '.') }}</p>
                                        <input type="hidden" name="items[{{ $item->id }}][id]"
                                            value="{{ $item->id }}">
                                        <div>
                                            <label for="quantity_{{ $item->id }}" class="text-sm">Jumlah:</label>
                                            <input type="number" name="items[{{ $item->id }}][quantity]"
                                                id="quantity_{{ $item->id }}"
                                                x-model.number="quantities[{{ $item->id }}]" min="0"
                                                placeholder="0"
                                                class="w-20 mt-1 px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                                                @input="updateTotal()">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-orange-600 mb-6 border-b pb-2">Detail Pemesan & Pengiriman</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @guest
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700">Nama Lengkap <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}"
                                required
                                class="mt-1 block w-full px-3 py-2 border {{ $errors->has('customer_name') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                            @error('customer_name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="customer_email" class="block text-sm font-medium text-gray-700">Email <span
                                    class="text-red-500">*</span></label>
                            <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email') }}"
                                required
                                class="mt-1 block w-full px-3 py-2 border {{ $errors->has('customer_email') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                            @error('customer_email')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="customer_phone" class="block text-sm font-medium text-gray-700">No. Telepon <span
                                    class="text-red-500">*</span></label>
                            <input type="tel" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}"
                                required
                                class="mt-1 block w-full px-3 py-2 border {{ $errors->has('customer_phone') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                            @error('customer_phone')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <div class="col-span-1 md:col-span-2">
                            <p class="text-gray-700">Anda memesan sebagai: <strong>{{ Auth::user()->name }}</strong>
                                ({{ Auth::user()->email }})</p>
                            <p class="text-sm text-gray-500">Detail nama dan email akan menggunakan data akun Anda. Alamat
                                pengiriman dan tanggal acara tetap perlu diisi.</p>
                        </div>
                    @endguest

                    <div class="md:col-span-2">
                        <label for="delivery_address" class="block text-sm font-medium text-gray-700">Alamat Pengiriman
                            Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="delivery_address" id="delivery_address" rows="3" required
                            class="mt-1 block w-full px-3 py-2 border {{ $errors->has('delivery_address') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">{{ old('delivery_address') }}</textarea>
                        @error('delivery_address')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="event_date" class="block text-sm font-medium text-gray-700">Tanggal Acara <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="event_date" id="event_date"
                            value="{{ old('event_date', date('Y-m-d', strtotime('+1 day'))) }}" required
                            min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            class="mt-1 block w-full px-3 py-2 border {{ $errors->has('event_date') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                        @error('event_date')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan Tambahan
                            (Opsional)</label>
                        <textarea name="notes" id="notes" rows="3"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="mb-8 p-6 bg-orange-50 rounded-lg">
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Ringkasan Pesanan</h3>
                <div class="text-lg font-bold text-gray-800">
                    Total Estimasi: Rp <span x-text="formatCurrency(totalAmount)">0</span>
                </div>
                <p class="text-sm text-gray-500 mt-1">Total akhir akan dikonfirmasi oleh tim kami.</p>
            </section>

            <div>
                <button type="submit"
                    class="w-full flex justify-center py-3 px-6 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out">
                    Kirim Pesanan
                </button>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
    <script>
        // Ambil semua item menu dari PHP ke JavaScript
        const menuItemsData = {
            @foreach ($categories as $category)
                @foreach ($category->menuItems as $item)
                    {{ $item->id }}: {
                        price: {{ $item->price }}
                    },
                @endforeach
            @endforeach
        };

        function orderForm() {
            return {
                quantities: {
                    // Inisialisasi quantities dari old input jika ada
                    @foreach ($categories as $category)
                        @foreach ($category->menuItems as $item)
                            '{{ $item->id }}': {{ old('items.' . $item->id . '.quantity', 0) }},
                        @endforeach
                    @endforeach
                },
                totalAmount: 0,
                init() {
                    this.updateTotal(); // Hitung total awal saat halaman dimuat
                    // Watch for changes in quantities to update total
                    this.$watch('quantities', () => this.updateTotal(), {
                        deep: true
                    });
                },
                updateTotal() {
                    let total = 0;
                    for (const itemId in this.quantities) {
                        if (this.quantities[itemId] > 0 && menuItemsData[itemId]) {
                            total += menuItemsData[itemId].price * this.quantities[itemId];
                        }
                    }
                    this.totalAmount = total;
                },
                formatCurrency(value) {
                    return new Intl.NumberFormat('id-ID').format(value);
                }
            }
        }

        // Pastikan Alpine.js diinisialisasi setelah DOM siap jika belum otomatis
        // Biasanya sudah ditangani oleh app.js jika Alpine diimpor di sana.
        // document.addEventListener('alpine:init', () => {
        //     Alpine.data('orderForm', orderForm);
        // });
    </script>
@endpush
