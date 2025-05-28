<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') -
        {{ app(\App\Models\Setting::class)->where('key', 'site_name')->first()?->value ?? config('app.name', 'Catering Laravel') }}
    </title>
    <link href="{{ asset('admin_theme/css/tabler.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin_theme/css/tabler-flags.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin_theme/css/tabler-payments.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin_theme/css/tabler-vendors.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin_theme/css/demo.min.css') }}" rel="stylesheet" />
    <style>
        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>

    @stack('styles')
</head>

<body>
    <script src="{{ asset('admin_theme/js/demo-theme.min.js') }}"></script>
    <div class="page">
        @include('admin.layouts.partials.header')
        @include('admin.layouts.partials.navbar')
        <div class="page-wrapper">
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            @yield('page-header')
                        </div>
                        <div class="col-auto ms-auto d-print-none">
                            @yield('page-actions')
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-body">
                <div class="container-xl">
                    {{-- Hapus atau komentari notifikasi alert Tabler yang lama --}}
                    {{-- @if (session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    {{ session('success') }}
                    <a href="#" class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    {{ session('error') }}
                    <a href="#" class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            @endif --}}

                    @yield('content')
                </div>
            </div>
            @include('admin.layouts.partials.footer')
        </div>
    </div>

    <script src="{{ asset('admin_theme/js/tabler.min.js') }}" defer></script>
    <script src="{{ asset('admin_theme/js/demo.min.js') }}" defer></script> {{-- Jika masih digunakan --}}

    {{-- SweetAlert JS (biasanya di-handle paket, tapi jika tidak, bisa ditambahkan dari CDN atau aset lokal) --}}
    {{-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

    @stack('scripts')

    @include('sweetalert::alert') {{-- <--- TAMBAHKAN BARIS INI DI SINI --}}
</body>

</html>
