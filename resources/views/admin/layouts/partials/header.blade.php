<header class="navbar navbar-expand-md d-print-none sticky-top">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
            aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center">
                @if (isset($siteSettings['site_logo']) &&
                        !empty($siteSettings['site_logo']) &&
                        Storage::disk('public')->exists($siteSettings['site_logo']))
                    <img src="{{ asset('storage/' . $siteSettings['site_logo']) }}"
                        alt="Logo {{ $siteSettings['site_name'] ?? '' }}" class="navbar-brand-image h-8 w-auto me-2">
                    {{-- Sesuaikan tinggi (h-8) dan margin (me-2) --}}
                @else
                    {{-- Fallback jika tidak ada logo, bisa berupa ikon atau teks awal nama situs --}}
                    {{-- <span class="navbar-brand-image avatar avatar-sm bg-primary-lt me-2">
                {{ strtoupper(substr($siteSettings['site_name'] ?? 'CL', 0, 2)) }}
            </span> --}}
                    <span class="navbar-brand-image me-2" style="font-size: 1.5rem;">🍰</span>
                @endif
                <span class="d-none d-sm-block"> {{-- Nama situs hanya tampil di layar lebih besar jika terlalu panjang --}}
                    {{ $siteSettings['site_name'] ?? config('app.name', 'Catering Lezat') }} Admin
                </span>
            </a>
        </h1>
        <div class="navbar-nav flex-row order-md-last">
            <div class="nav-item d-none d-md-flex me-3">
                <a href="{{ route('home') }}" class="btn btn-ghost-secondary" target="_blank" rel="noopener"
                    title="Lihat Website Publik">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-external-link"
                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" />
                        <path d="M11 13l9 -9" />
                        <path d="M15 4h5v5" />
                    </svg>
                    Lihat Website
                </a>
            </div>
            @auth
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                        aria-label="Open user menu">
                        <span class="avatar avatar-sm"
                            style="background-image: url(https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF)"></span>
                        <div class="d-none d-xl-block ps-2">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="mt-1 small text-muted">Administrator</div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        {{-- <a href="#" class="dropdown-item">Status</a> --}}
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">Profil Saya (Publik)</a>
                        {{-- Link ke profil Breeze --}}
                        {{-- <a href="#" class="dropdown-item">Feedback</a> --}}
                        <div class="dropdown-divider"></div>
                        {{-- <a href="./settings.html" class="dropdown-item">Settings</a> --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" class="dropdown-item"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Logout
                            </a>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</header>
