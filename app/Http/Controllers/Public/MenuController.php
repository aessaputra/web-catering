<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil semua kategori menu yang memiliki item menu
        $categories = MenuCategory::whereHas('menuItems') // Hanya kategori yang punya item
            ->with(['menuItems' => function ($query) {
                // Anda bisa menambahkan kondisi tambahan untuk item menu di sini jika perlu
                // Misalnya, hanya item yang aktif: $query->where('is_active', true);
            }])
            ->orderBy('name', 'asc')
            ->get();

        // Alternatif jika Anda ingin menampilkan semua item dan mengelompokkannya di view:
        // $menuItems = MenuItem::with('menuCategory')->orderBy('name', 'asc')->get();
        // $categories = MenuCategory::orderBy('name', 'asc')->get();
        // Namun, pendekatan di atas (mengambil kategori dengan itemnya) lebih efisien jika Anda ingin menampilkan per kategori.

        return view('public.menu_list', compact('categories'));
    }

    /**
     * Display the specified resource.
     * (Ini bisa untuk halaman detail item menu jika diperlukan nanti)
     */
    // public function show(MenuItem $menuItem) // Menggunakan Route Model Binding
    // {
    //     // $menuItem sudah otomatis di-load berdasarkan slug atau ID dari route
    //     return view('public.menu.show', compact('menuItem'));
    // }

    /**
     * Display menu items by category.
     * (Ini bisa untuk halaman filter per kategori jika diperlukan nanti)
     */
    // public function category(MenuCategory $category) // Menggunakan Route Model Binding
    // {
    //     $menuItems = $category->menuItems()->paginate(10); // Contoh dengan paginasi
    //     return view('public.menu.category_list', compact('category', 'menuItems'));
    // }
}
