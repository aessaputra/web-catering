<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
      <div class="navbar">
        <div class="container-xl">
          <ul class="navbar-nav">
            <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('admin.dashboard') }}" >
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                </span>
                <span class="nav-link-title">
                  Dashboard
                </span>
              </a>
            </li>
            <li class="nav-item dropdown {{ Str::startsWith(request()->route()->getName(), 'admin.categories') || Str::startsWith(request()->route()->getName(), 'admin.menu-items') ? 'active' : '' }}">
              <a class="nav-link dropdown-toggle" href="#navbar-menu-management" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="{{ Str::startsWith(request()->route()->getName(), 'admin.categories') || Str::startsWith(request()->route()->getName(), 'admin.menu-items') ? 'true' : 'false' }}" >
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-tools-kitchen-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                     <path d="M19 3v12h-5c-.023 -3.681 .184 -7.406 .534 -11h2.466l.001 -1z" />
                     <path d="M5 12v9h5c.023 -3.681 -.184 -7.406 -.534 -11h-2.466l-.001 -1z" />
                     <path d="M8 12l0 9" /><path d="M11 12l0 9" /><path d="M14 12l0 9" /><path d="M17 12l0 9" />
                     <path d="M5 3v4c0 2.761 2.239 5 5 5h4c2.761 0 5 -2.239 5 -5v-4" />
                  </svg>
                </span>
                <span class="nav-link-title">
                  Manajemen Menu
                </span>
              </a>
              <div class="dropdown-menu {{ Str::startsWith(request()->route()->getName(), 'admin.categories') || Str::startsWith(request()->route()->getName(), 'admin.menu-items') ? 'show' : '' }}">
                <a class="dropdown-item {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                  Kategori Menu
                </a>
                <a class="dropdown-item {{ request()->routeIs('admin.menu-items.index') ? 'active' : '' }}" href="{{ route('admin.menu-items.index') }}">
                  Item Menu
                </a>
              </div>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('admin.orders.index') }}" >
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-truck-delivery" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                     <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                     <path d="M5 17h-2v-4m-1 -8h11v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" /><path d="M3 9l4 0" />
                  </svg>
                </span>
                <span class="nav-link-title">
                  Manajemen Pesanan
                </span>
              </a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('admin.customers.index') }}" >
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                     <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                  </svg>
                </span>
                <span class="nav-link-title">
                  Manajemen Pelanggan
                </span>
              </a>
            </li>
             <li class="nav-item {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('admin.settings.index') }}" >
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                     <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                  </svg>
                </span>
                <span class="nav-link-title">
                  Pengaturan Website
                </span>
              </a>
            </li>
            {{-- <li class="nav-item">
              <a class="nav-link" href="#">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users-group" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" /><path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M17 10h2a2 2 0 0 1 2 2v1" /><path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M3 13v-1a2 2 0 0 1 2 -2h2" /></svg>
                </span>
                <span class="nav-link-title">
                  Manajemen Admin
                </span>
              </a>
            </li> --}}
          </ul>
        </div>
      </div>
    </div>
  </header>