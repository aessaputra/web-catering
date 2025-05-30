<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
            <div class="container-xl">
                <ul class="navbar-nav">
                    {{-- Dashboard --}}
                    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                    <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                    <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Dashboard
                            </span>
                        </a>
                    </li>

                    {{-- Manajemen Menu Dropdown --}}
                    <li
                        class="nav-item dropdown {{ Str::startsWith(request()->route()->getName(), ['admin.categories', 'admin.menu-items']) ? 'active' : '' }}">
                        <a class="nav-link dropdown-toggle" href="#navbar-menu-management" data-bs-toggle="dropdown"
                            data-bs-auto-close="true" role="button"
                            aria-expanded="{{ Str::startsWith(request()->route()->getName(), ['admin.categories', 'admin.menu-items']) ? 'true' : 'false' }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-tools-kitchen-2" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M19 3v12h-5c-.023 -3.681 .184 -7.406 .534 -11h2.466l.001 -1z" />
                                    <path d="M5 12v9h5c.023 -3.681 -.184 -7.406 -.534 -11h-2.466l-.001 -1z" />
                                    <path d="M8 12l0 9" />
                                    <path d="M11 12l0 9" />
                                    <path d="M14 12l0 9" />
                                    <path d="M17 12l0 9" />
                                    <path d="M5 3v4c0 2.761 2.239 5 5 5h4c2.761 0 5 -2.239 5 -5v-4" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Manajemen Menu
                            </span>
                        </a>
                        <div class="dropdown-menu"> {{-- Tidak ada kelas 'show' dinamis di sini --}}
                            <a class="dropdown-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                                href="{{ route('admin.categories.index') }}">
                                Kategori Menu
                            </a>
                            <a class="dropdown-item {{ request()->routeIs('admin.menu-items.*') ? 'active' : '' }}"
                                href="{{ route('admin.menu-items.index') }}">
                                Item Menu
                            </a>
                        </div>
                    </li>

                    {{-- Manajemen Pesanan --}}
                    <li class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.orders.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-truck-delivery" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M5 17h-2v-4m-1 -8h11v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" />
                                    <path d="M3 9l4 0" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Manajemen Pesanan
                            </span>
                        </a>
                    </li>

                    {{-- Manajemen Pelanggan --}}
                    <li class="nav-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.customers.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                    <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Manajemen Pelanggan
                            </span>
                        </a>
                    </li>

                    {{-- Arsip Pesan Kontak --}}
                    <li class="nav-item {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.contact-messages.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail-opened"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 9l9 6l9 -6l-9 -6l-9 6" />
                                    <path d="M21 9v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10" />
                                    <path d="M3 19l9 -6" />
                                    <path d="M15 13l6 -4" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Arsip Pesan Kontak
                            </span>
                        </a>
                    </li>

                    {{-- Dropdown Pengaturan --}}
                    <li
                        class="nav-item dropdown {{ request()->routeIs('admin.settings.general.index') || request()->routeIs('admin.settings.about.index')
                            ? 'active'
                            : '' }}">
                        <a class="nav-link dropdown-toggle" href="#navbar-settings-dropdown"
                            data-bs-toggle="dropdown" data-bs-auto-close="true" {{-- Memastikan dropdown menutup saat item diklik --}} role="button"
                            aria-expanded="{{ request()->routeIs('admin.settings.general.index') || request()->routeIs('admin.settings.about.index')
                                ? 'true'
                                : 'false' }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-adjustments-horizontal" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M14 6m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M4 6l8 0" />
                                    <path d="M16 6l4 0" />
                                    <path d="M8 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M4 12l2 0" />
                                    <path d="M10 12l10 0" />
                                    <path d="M17 18m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M4 18l11 0" />
                                    <path d="M19 18l1 0" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Pengaturan Situs
                            </span>
                        </a>
                        <div class="dropdown-menu"> {{-- Tidak ada kondisi kelas 'show' di sini --}}
                            <a class="dropdown-item {{ request()->routeIs('admin.settings.general.index') ? 'active' : '' }}"
                                href="{{ route('admin.settings.general.index') }}"> {{-- Tidak perlu hash jika halaman terpisah --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-tool me-2"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l-3.5 -3.5v-3h-3" />
                                </svg>
                                Pengaturan Umum & Branding
                            </a>
                            <a class="dropdown-item {{ request()->routeIs('admin.settings.about.index') ? 'active' : '' }}"
                                href="{{ route('admin.settings.about.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-info-circle me-2" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                    <path d="M12 9h.01" />
                                    <path d="M11 12h1v4h1" />
                                </svg>
                                Konten "Tentang Kami"
                            </a>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</header>
