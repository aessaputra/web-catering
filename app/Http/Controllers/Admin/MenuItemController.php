<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\MenuCategory;
use App\Http\Requests\Admin\StoreMenuItemRequest;
use App\Http\Requests\Admin\UpdateMenuItemRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MenuItem::with('menuCategory')->orderBy('name', 'asc');

        // Fitur Pencarian (opsional)
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Fitur Filter berdasarkan Kategori (opsional)
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('menu_category_id', $request->category_id);
        }

        $menuItems = $query->paginate(10)->withQueryString(); // withQueryString agar parameter search/filter tetap ada di link paginasi
        $categories = MenuCategory::orderBy('name', 'asc')->get(); // Untuk dropdown filter

        return view('admin.menu_items.index', compact('menuItems', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = MenuCategory::orderBy('name', 'asc')->get();
        if ($categories->isEmpty()) {
            return redirect()->route('admin.categories.create')->with('error', 'Anda harus membuat kategori menu terlebih dahulu sebelum menambahkan item menu.');
        }
        return view('admin.menu_items.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuItemRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menu_items', 'public');
            $validatedData['image_path'] = $path;
        }

        $validatedData['is_featured'] = $request->has('is_featured');

        MenuItem::create($validatedData);

        Alert::success('Berhasil!', 'Item menu berhasil ditambahkan.');

        return redirect()->route('admin.menu-items.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MenuItem $menuItem) // Route model binding
    {
        $categories = MenuCategory::orderBy('name', 'asc')->get();
        return view('admin.menu_items.edit', compact('menuItem', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuItemRequest $request, MenuItem $menuItem)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada dan bukan gambar default
            if ($menuItem->image_path && Storage::disk('public')->exists($menuItem->image_path)) {
                Storage::disk('public')->delete($menuItem->image_path);
            }
            // Upload gambar baru
            $path = $request->file('image')->store('menu_items', 'public');
            $validatedData['image_path'] = $path;
        }

        $validatedData['is_featured'] = $request->has('is_featured');

        $menuItem->update($validatedData);

        Alert::success('Berhasil!', 'Item menu berhasil diperbarui.');

        return redirect()->route('admin.menu-items.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuItem $menuItem)
    {
        // Hapus gambar terkait jika ada
        if ($menuItem->image_path && Storage::disk('public')->exists($menuItem->image_path)) {
            Storage::disk('public')->delete($menuItem->image_path);
        }

        $menuItem->delete();

        Alert::success('Berhasil!', 'Item menu berhasil dihapus.');

        return redirect()->route('admin.menu-items.index');
    }
}
