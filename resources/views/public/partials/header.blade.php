<header class="bg-white shadow-md sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center justify-between h-16">
            {{-- Logo dan Nama Situs --}}
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="flex items-center text-xl lg:text-2xl font-bold text-orange-600">
                    @if (isset($siteSettings['site_logo']) &&
                            $siteSettings['site_logo'] &&
                            Storage::disk('public')->exists($siteSettings['site_logo']))
                        <img src="{{ asset('storage/' . $siteSettings['site_logo']) }}"
                            alt="Logo {{ $siteSettings['site_name'] ?? '' }}" class="h-8 w-auto mr-2 sm:h-10">
                    @else
                        <span class="mr-2 text-2xl sm:text-3xl"><i
                                class="fas fa-birthday-cake fa-fw text-2xl sm:text-3xl mr-2 text-orange-500"></i></span>
                    @endif
                    <span>
                        {{ $siteSettings['site_name'] ?? 'Catering Lezat' }}
                    </span>
                </a>
            </div>

            {{-- Navigasi Desktop --}}
            <div class="hidden md:flex md:items-center md:space-x-3 lg:space-x-4">
                <a href="{{ route('home') }}"
                    class="px-2 lg:px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('home') ? 'text-orange-600 border-b-2 border-orange-600' : 'text-gray-700 hover:text-orange-500' }} whitespace-nowrap">Beranda</a>
                <a href="{{ route('menu.index') }}"
                    class="px-2 lg:px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('menu.index') ? 'text-orange-600 border-b-2 border-orange-600' : 'text-gray-700 hover:text-orange-500' }} whitespace-nowrap">Daftar
                    Menu</a>
                <a href="{{ route('about') }}"
                    class="px-2 lg:px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('about') ? 'text-orange-600 border-b-2 border-orange-600' : 'text-gray-700 hover:text-orange-500' }} whitespace-nowrap">Tentang
                    Kami</a>
                <a href="{{ route('contact.index') }}"
                    class="px-2 lg:px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('contact.index') ? 'text-orange-600 border-b-2 border-orange-600' : 'text-gray-700 hover:text-orange-500' }} whitespace-nowrap">Kontak</a>

                {{-- Tombol Pesan Sekarang (Desktop) --}}
                <a href="{{ route('order.create') }}"
                    class="inline-flex items-center justify-center min-w-[14px]
                          px-3 py-2 rounded-md text-sm font-medium text-white 
                          bg-orange-500 hover:bg-orange-600 
                          focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 
                          whitespace-nowrap"><i
                        class="fas fa-shopping-cart fa-fw mr-1"></i>
                    Pesan Sekarang
                </a>

                {{-- Auth Links Desktop --}}
                <div class="ml-2 lg:ml-4">
                    @guest
                        {{-- Tombol Masuk (Desktop) --}}
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center justify-center min-w-[14px] {{-- Samakan nilai min-w --}}
                                  px-3 py-2 rounded-md text-sm font-medium text-white 
                                  bg-orange-500 hover:bg-orange-600 
                                  focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 
                                  whitespace-nowrap">
                            Masuk
                        </a>
                    @else
                        <div class="relative" x-data="{ userDropdownOpen: false }">
                            <button @click="userDropdownOpen = !userDropdownOpen"
                                class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 whitespace-nowrap">
                                <span class="truncate max-w-[100px] sm:max-w-[150px]">{{ Auth::user()->name }}</span>
                                <svg class="ml-1 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="userDropdownOpen" @click.away="userDropdownOpen = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1"
                                style="display: none;">
                                <a href="{{ route('dashboard') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                    role="menuitem" tabindex="-1" id="user-menu-item-0">Dashboard Saya</a>
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                    role="menuitem" tabindex="-1" id="user-menu-item-1">Profil Saya</a>
                                <form method="POST" action="{{ route('logout') }}" role="none">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                        role="menuitem" tabindex="-1" id="user-menu-item-2">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>

            {{-- Tombol Hamburger untuk Mobile --}}
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-orange-500"
                    aria-controls="mobile-menu" :aria-expanded="mobileMenuOpen.toString()">
                    <span class="sr-only">Open main menu</span>
                    <svg x-show="!mobileMenuOpen" class="h-6 w-6 block" stroke="currentColor" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" class="h-6 w-6 block" stroke="currentColor" fill="none"
                        viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </nav>
    </div>

    {{-- Menu Mobile --}}
    <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="md:hidden border-t border-gray-200" id="mobile-menu" style="display: none;">

        {{-- Grup untuk link navigasi biasa --}}
        <div class="px-2 pt-2 pb-2 space-y-1 sm:px-3">
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
        </div>

        {{-- Grup untuk tombol aksi utama (Pesan Sekarang & Masuk jika guest) --}}
        <div class="px-4 pb-3 pt-2 space-y-2 border-t border-gray-200">
            <a href="{{ route('order.create') }}"
                class="block w-full px-3 py-2 rounded-md text-base font-medium text-white bg-orange-500 hover:bg-orange-600 text-center">
                Pesan Sekarang {{-- Ikon 🛒 bisa dihapus jika ingin sama persis dengan tombol Masuk --}}
            </a>
            @guest
                <a href="{{ route('login') }}"
                    class="block w-full px-3 py-2 rounded-md text-base font-medium text-white bg-orange-500 hover:bg-orange-600 text-center">
                    Masuk
                </a>
            @endguest
        </div>

        {{-- Auth Links Mobile (jika sudah login) --}}
        @auth
            <div class="pt-4 pb-3 border-t border-gray-200"> {{-- Tambahkan border-t jika sebelumnya ada tombol aksi --}}
                <div class="px-5">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <div class="mt-3 px-2 space-y-1">
                    <a href="{{ route('dashboard') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-orange-500">Dashboard
                        Saya</a>
                    <a href="{{ route('profile.edit') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-orange-500">Profil
                        Saya</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-orange-500">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</header>
