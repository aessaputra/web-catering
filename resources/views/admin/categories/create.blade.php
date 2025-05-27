@extends('admin.layouts.app')

@section('title', 'Tambah Kategori Menu Baru')

@section('page-header')
    <div class="page-pretitle">Kategori Menu</div>
    <h2 class="page-title">Tambah Kategori Baru</h2>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @include('admin.categories._form')
        </form>
    </div>
</div>
@endsection