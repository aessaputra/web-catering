@extends('admin.layouts.app')

@section('title', 'Tambah Item Menu Baru')

@section('page-header')
    <div class="page-pretitle">Manajemen Menu</div>
    <h2 class="page-title">Tambah Item Menu Baru</h2>
@endsection

@section('content')
    <form action="{{ route('admin.menu-items.store') }}" method="POST" enctype="multipart/form-data">
        @include('admin.menu_items._form')
    </form>
@endsection
