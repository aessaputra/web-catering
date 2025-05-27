@extends('admin.layouts.app')

@section('title', 'Edit Kategori Menu: ' . $category->name)

@section('page-header')
    <div class="page-pretitle">Kategori Menu</div>
    <h2 class="page-title">Edit Kategori: {{ $category->name }}</h2>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @method('PUT')
            @include('admin.categories._form', ['category' => $category])
        </form>
    </div>
</div>
@endsection