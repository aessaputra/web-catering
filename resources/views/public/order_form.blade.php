@extends('public.layouts.app')

@section('title', 'Formulir Pemesanan Anda')

@push('styles')
    <style>
        input[type='number']::-webkit-outer-spin-button,
        input[type='number']::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type='number'] {
            -moz-appearance: textfield;
        }
    </style>
@endpush

@section('content')
    <div class="bg-gray-100 min-h-screen">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <div class="text-center mb-10 md:mb-14">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800">
                    <i class="fas fa-shopping-basket text-orange-500 mr-2"></i>Isi Formulir Pesanan Anda
                </h1>
                <p class="mt-3 text-lg text-gray-600 max-w-xl mx-auto">Pilih menu favorit Anda dan lengkapi detail pesanan di
                    bawah ini.</p>
            </div>

            {{-- Notifikasi (SweetAlert akan otomatis menangani dari controller) --}}
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 mb-6 rounded-md shadow-md" role="alert">
                    <div class="flex">
                        <div class="py-1"><svg class="fill-current h-6 w-6 text-red-500 mr-4"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path
                                    d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                            </svg></div>
                        <div>
                            <p class="font-bold">Harap perbaiki kesalahan berikut:</p>
                            <ul class="mt-1 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('order.store') }}" method="POST" class="bg-white p-6 sm:p-8 rounded-xl shadow-2xl"
                x-data="orderForm()">
                @csrf

                <section class="mb-10">
                    <h2
                        class="text-2xl font-semibold text-orange-600 mb-6 border-b-2 border-orange-200 pb-3 flex items-center">
                        <i class="fas fa-utensils fa-fw mr-3 text-xl"></i>Pilih Menu Anda
                    </h2>
                    @if ($categories->isEmpty())
                        <p class="text-gray-600 py-6 text-center">Maaf, belum ada menu yang tersedia untuk dipesan saat ini.
                        </p>
                    @else
                        @foreach ($categories as $category)
                            <div class="mb-8">
                                <h3 class="text-xl font-bold text-gray-700 mb-4 flex items-center">
                                    {{ $category->name }}
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach ($category->menuItems as $item)
                                        @php
                                            $quantityErrorKey = 'items.' . $item->id . '.quantity';
                                            $quantityInputClasses = [
                                                'w-20',
                                                'px-3',
                                                'py-1.5',
                                                'border',
                                                'rounded-md',
                                                'shadow-sm',
                                                'focus:outline-none',
                                                'focus:ring-1',
                                                'focus:ring-orange-500',
                                                'focus:border-orange-500',
                                                'sm:text-sm',
                                            ];
                                            if ($errors->has($quantityErrorKey)) {
                                                $quantityInputClasses[] = 'border-red-500';
                                            } else {
                                                $quantityInputClasses[] = 'border-gray-300';
                                            }
                                        @endphp
                                        <div
                                            class="bg-gray-50 border border-gray-200 p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow flex flex-col">
                                            <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : 'https://via.placeholder.com/300x200.png?text=' . urlencode($item->name) }}"
                                                alt="{{ $item->name }}" class="w-full h-40 object-cover rounded-md mb-3">
                                            <h4 class="font-semibold text-md text-gray-800">{{ $item->name }}</h4>
                                            <p class="text-xs text-gray-500 mb-2">{{ Str::limit($item->description, 60) }}
                                            </p>
                                            <p class="text-orange-600 font-bold text-lg mb-3">Rp
                                                {{ number_format($item->price, 0, ',', '.') }}</p>

                                            <input type="hidden" name="items[{{ $item->id }}][id]"
                                                value="{{ $item->id }}">
                                            <div class="mt-auto flex items-center space-x-2">
                                                <label for="quantity_{{ $item->id }}"
                                                    class="text-sm text-gray-600">Jumlah:</label>
                                                <input type="number" name="items[{{ $item->id }}][quantity]"
                                                    id="quantity_{{ $item->id }}"
                                                    x-model.number="quantities[{{ $item->id }}]" min="0"
                                                    placeholder="0" class="{{ implode(' ', $quantityInputClasses) }}"
                                                    @input="updateTotal()">
                                            </div>
                                            @error($quantityErrorKey)
                                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif
                </section>

                <section class="mb-10">
                    <h2
                        class="text-2xl font-semibold text-orange-600 mb-6 border-b-2 border-orange-200 pb-3 flex items-center">
                        <i class="fas fa-user-circle fa-fw mr-3 text-xl"></i>Detail Pemesan & Pengiriman
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        @guest
                            {{-- Customer Name --}}
                            @php
                                $customerNameClasses = [
                                    'block',
                                    'w-full',
                                    'px-4',
                                    'py-2.5',
                                    'border',
                                    'rounded-lg',
                                    'shadow-sm',
                                    'focus:outline-none',
                                    'focus:ring-2',
                                    'focus:ring-orange-500',
                                    'focus:border-orange-500',
                                    'sm:text-sm',
                                ];
                                if ($errors->has('customer_name')) {
                                    $customerNameClasses[] = 'border-red-500';
                                } else {
                                    $customerNameClasses[] = 'border-gray-300';
                                }
                            @endphp
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="customer_name" id="customer_name"
                                    value="{{ old('customer_name') }}" required
                                    class="{{ implode(' ', $customerNameClasses) }}" placeholder="Masukkan nama lengkap Anda">
                                @error('customer_name')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Customer Email --}}
                            @php
                                $customerEmailClasses = [
                                    'block',
                                    'w-full',
                                    'px-4',
                                    'py-2.5',
                                    'border',
                                    'rounded-lg',
                                    'shadow-sm',
                                    'focus:outline-none',
                                    'focus:ring-2',
                                    'focus:ring-orange-500',
                                    'focus:border-orange-500',
                                    'sm:text-sm',
                                ];
                                if ($errors->has('customer_email')) {
                                    $customerEmailClasses[] = 'border-red-500';
                                } else {
                                    $customerEmailClasses[] = 'border-gray-300';
                                }
                            @endphp
                            <div>
                                <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">Email <span
                                        class="text-red-500">*</span></label>
                                <input type="email" name="customer_email" id="customer_email"
                                    value="{{ old('customer_email') }}" required
                                    class="{{ implode(' ', $customerEmailClasses) }}" placeholder="email@anda.com">
                                @error('customer_email')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Customer Phone --}}
                            @php
                                $customerPhoneClasses = [
                                    'block',
                                    'w-full',
                                    'px-4',
                                    'py-2.5',
                                    'border',
                                    'rounded-lg',
                                    'shadow-sm',
                                    'focus:outline-none',
                                    'focus:ring-2',
                                    'focus:ring-orange-500',
                                    'focus:border-orange-500',
                                    'sm:text-sm',
                                ];
                                if ($errors->has('customer_phone')) {
                                    $customerPhoneClasses[] = 'border-red-500';
                                } else {
                                    $customerPhoneClasses[] = 'border-gray-300';
                                }
                            @endphp
                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">No.
                                    Telepon/WhatsApp <span class="text-red-500">*</span></label>
                                <input type="tel" name="customer_phone" id="customer_phone"
                                    value="{{ old('customer_phone') }}" required
                                    class="{{ implode(' ', $customerPhoneClasses) }}" placeholder="08xxxxxxxxxx">
                                @error('customer_phone')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <div class="col-span-1 md:col-span-2 bg-orange-50 p-4 rounded-lg">
                                <p class="text-gray-700 font-medium">Anda memesan sebagai:
                                    <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->email }})</p>
                                <p class="text-sm text-gray-600 mt-1">Nama, email, dan no. telepon akan menggunakan data akun
                                    Anda. Anda hanya perlu mengisi alamat pengiriman dan tanggal acara.</p>
                            </div>
                        @endguest

                        {{-- Delivery Address --}}
                        @php
                            $deliveryAddressClasses = [
                                'block',
                                'w-full',
                                'px-4',
                                'py-2.5',
                                'border',
                                'rounded-lg',
                                'shadow-sm',
                                'focus:outline-none',
                                'focus:ring-2',
                                'focus:ring-orange-500',
                                'focus:border-orange-500',
                                'sm:text-sm',
                            ];
                            if ($errors->has('delivery_address')) {
                                $deliveryAddressClasses[] = 'border-red-500';
                            } else {
                                $deliveryAddressClasses[] = 'border-gray-300';
                            }
                        @endphp
                        <div class="md:col-span-2">
                            <label for="delivery_address" class="block text-sm font-medium text-gray-700 mb-1">Alamat
                                Pengiriman Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="delivery_address" id="delivery_address" rows="3" required
                                class="{{ implode(' ', $deliveryAddressClasses) }}"
                                placeholder="Jl. Contoh No. 1, Kelurahan, Kecamatan, Kota, Kode Pos">{{ old('delivery_address') }}</textarea>
                            @error('delivery_address')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Event Date --}}
                        @php
                            $eventDateClasses = [
                                'block',
                                'w-full',
                                'px-4',
                                'py-2.5',
                                'border',
                                'rounded-lg',
                                'shadow-sm',
                                'focus:outline-none',
                                'focus:ring-2',
                                'focus:ring-orange-500',
                                'focus:border-orange-500',
                                'sm:text-sm',
                            ];
                            if ($errors->has('event_date')) {
                                $eventDateClasses[] = 'border-red-500';
                            } else {
                                $eventDateClasses[] = 'border-gray-300';
                            }
                        @endphp
                        <div class="md:col-span-1">
                            <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Acara
                                <span class="text-red-500">*</span></label>
                            <input type="date" name="event_date" id="event_date"
                                value="{{ old('event_date', date('Y-m-d', strtotime('+3 day'))) }}" required
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                class="{{ implode(' ', $eventDateClasses) }}">
                            @error('event_date')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Notes --}}
                        @php
                            $notesClasses = [
                                'block',
                                'w-full',
                                'px-4',
                                'py-2.5',
                                'border',
                                'border-gray-300',
                                'rounded-lg',
                                'shadow-sm',
                                'focus:outline-none',
                                'focus:ring-2',
                                'focus:ring-orange-500',
                                'focus:border-orange-500',
                                'sm:text-sm',
                            ];
                            // Tidak ada error class khusus untuk notes karena nullable
                        @endphp
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan
                                (Opsional)</label>
                            <textarea name="notes" id="notes" rows="3" class="{{ implode(' ', $notesClasses) }}"
                                placeholder="Preferensi khusus, permintaan waktu antar, dll.">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>

                <section class="mb-8 p-6 bg-orange-100 rounded-xl shadow-inner">
                    <h3 class="text-xl font-semibold text-orange-700 mb-3 flex items-center">
                        <i class="fas fa-receipt fa-fw mr-2"></i>Ringkasan Pesanan Anda
                    </h3>
                    <div class="text-2xl font-bold text-gray-800">
                        Total Estimasi: <span class="text-orange-600">Rp <span
                                x-text="formatCurrency(totalAmount)">0</span></span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Total akhir akan dikonfirmasi oleh tim kami setelah pesanan Anda
                        kami proses.</p>
                </section>

                <div>
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center py-3.5 px-6 border border-transparent rounded-lg shadow-lg text-lg font-semibold text-white bg-orange-500 hover:bg-orange-600 active:bg-orange-700 focus:outline-none focus:ring-4 focus:ring-orange-300 focus:ring-offset-2 transition duration-150 ease-in-out">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Pesanan Saya
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Script Alpine.js tetap sama seperti sebelumnya --}}
    <script>
        const menuItemsData = {
            @foreach ($categories as $category)
                @foreach ($category->menuItems as $item)
                    {{ $item->id }}: {
                        price: {{ $item->price }},
                        name: "{{ e($item->name) }}"
                    },
                @endforeach
            @endforeach
        };

        function orderForm() {
            return {
                quantities: {
                    @php
                        $oldItems = old('items', []);
                    @endphp
                    @foreach ($categories as $category)
                        @foreach ($category->menuItems as $item)
                            '{{ $item->id }}': {{ $oldItems[$item->id]['quantity'] ?? (request()->query('add-item') == $item->id ? 1 : 0) }},
                        @endforeach
                    @endforeach
                },
                totalAmount: 0,
                init() {
                    this.updateTotal();
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
    </script>
@endpush
