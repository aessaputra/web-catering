<header class="bg-white shadow-md">
    <nav class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-orange-600">
                    🍰 {{-- config('app.name', 'Catering Lezat') --}}
                    {{-- Mengambil dari database settings jika ada --}}
                    {{ app(\App\Models\Setting::class)->where('key', 'site_name')->first()?->value ?? config('app.name', 'Catering Lezat') }}
                </a>
            </div>
            <div class="hidden md:flex space-x-4">
                <a href="{{ route('home') }}"
                    class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('home') ? 'text-orange-600 border-b-2 border-orange-600' : 'text-gray-700 hover:text-orange-500' }}">Beranda</a>
                <a href="{{ route('menu.index') }}"
                    class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('menu.index') ? 'text-orange-600 border-b-2 border-orange-600' : 'text-gray-700 hover:text-orange-500' }}">Daftar
                    Menu</a>
                <a href="{{ route('about') }}"
                    class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('about') ? 'text-orange-600 border-b-2 border-orange-600' : 'text-gray-700 hover:text-orange-500' }}">Tentang
                    Kami</a>
                <a href="{{ route('contact.index') }}"
                    class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('contact.index') ? 'text-orange-600 border-b-2 border-orange-600' : 'text-gray-700 hover:text-orange-500' }}">Kontak</a>
                <a href="{{ route('order.create') }}"
                    class="px-3 py-2 rounded-md text-sm font-medium text-white bg-orange-500 hover:bg-orange-600">🛒
                    Pesan Sekarang</a>
            </div>
            <div class="hidden md:flex items-center space-x-2">
                @guest
                    <a href="{{ route('login') }}"
                        class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-orange-500">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-orange-500">Register</a>
                    @endif
                @else
                    <a href="{{ route('dashboard') }}"
                        class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-orange-500">Dashboard
                        Saya</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                            class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-orange-500">
                            Logout
                        </a>
                    </form>
                @endguest
            </div>
            <div class="md:hidden flex items-center">
                <button @click="open = !open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-orange-500"
                    aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <div x-show="open" x-transition class="md:hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('home') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('home') ? 'text-orange-600 bg-orange-50' : 'text-gray-700 hover:bg-gray-50 hover:text-orange-500' }}">Beranda</a>
                <a href="{{ route('menu.index') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('menu.index') ? 'text-orange-600 bg-orange-50' : 'text-gray-700 hover:bg-gray-50 hover:text-orange-500' }}">Daftar
                    Menu</a>
                <a href="{{ route('about') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('about') ? 'text-orange-600 bg-orange-50' : 'text-gray-700 hover:bg-gray-50 hover:text-orange-500' }}">Tentang
                    Kami</a>
                <a href="{{ route('contact.index') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('contact.index') ? 'text-orange-600 bg-orange-50' : 'text-gray-700 hover:bg-gray-50 hover:text-orange-500' }}">Kontak</a>
                <a href="{{ route('order.create') }}"
                    class="mt-2 block w-full px-3 py-2 rounded-md text-base font-medium text-white bg-orange-500 hover:bg-orange-600 text-center">🛒
                    Pesan Sekarang</a>
            </div>
            <div class="pt-4 pb-3 border-t border-gray-200">
                @guest
                    <div class="px-2 space-y-1">
                        <a href="{{ route('login') }}"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-orange-500">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-orange-500">Register</a>
                        @endif
                    </div>
                @else
                    <div class="px-5">
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="mt-3 px-2 space-y-1">
                        <a href="{{ route('dashboard') }}"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-orange-500">Dashboard
                            Saya</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-orange-500">
                                Logout
                            </a>
                        </form>
                    </div>
                @endguest
            </div>
        </div>
    </nav>
    <script>
        // Simple Alpine.js for mobile menu toggle
        document.addEventListener('alpine:init', () => {
            Alpine.data('navigationMenu', () => ({
                open: false,
            }))
        })
    </script>
</header>
