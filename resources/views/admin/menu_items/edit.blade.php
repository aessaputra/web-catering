@extends('admin.layouts.app')

@section('title', 'Edit Item Menu: ' . $menuItem->name)

@section('page-header')
    <div class="page-pretitle">Manajemen Menu</div>
    <h2 class="page-title">Edit Item Menu: {{ $menuItem->name }}</h2>
@endsection

@section('content')
    <form action="{{ route('admin.menu-items.update', $menuItem) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.menu_items._form', ['menuItem' => $menuItem])
    </form>
@endsection
