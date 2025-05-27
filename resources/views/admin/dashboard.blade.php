@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')

@section('page-header')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Ringkasan
                    </div>
                    <h2 class="page-title">
                        Dashboard Admin 📈
                    </h2>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row row-deck row-cards">
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">Total Pesanan</div>
                    </div>
                    <div class="h1 mb-3">{{ $totalOrders ?? 0 }}</div>
                    <div class="d-flex mb-2">
                        <div>Pesanan masuk keseluruhan</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">Total Pendapatan (Selesai)</div>
                    </div>
                    <div class="h1 mb-3">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
                    <div class="d-flex mb-2">
                        <div>Dari pesanan yang selesai</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">Pelanggan Baru (Hari Ini)</div>
                    </div>
                    <div class="h1 mb-3">{{ $newCustomers ?? 0 }}</div>
                    <div class="d-flex mb-2">
                        <div>Registrasi pelanggan hari ini</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">Total Item Menu</div>
                    </div>
                    <div class="h1 mb-3">{{ $totalMenuItems ?? 0 }}</div>
                    <div class="d-flex mb-2">
                        <div>Jumlah variasi menu</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
