<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Http\Requests\Admin\StoreMenuCategoryRequest;
use App\Http\Requests\Admin\UpdateMenuCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = MenuCategory::withCount('menuItems')
            ->orderBy('name', 'asc')
            ->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuCategoryRequest $request)
    {
        $validated = $request->validated();
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        // Pastikan slug unik jika di-generate ulang di sini
        $count = MenuCategory::where('slug', $validated['slug'])->count();
        if ($count > 0) {
            $validated['slug'] = $validated['slug'] . '-' . ($count + 1);
        }


        MenuCategory::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori menu berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(MenuCategory $menuCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MenuCategory $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuCategoryRequest $request, MenuCategory $category)
    {
        $validated = $request->validated();
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Pastikan slug unik saat update, kecuali untuk diri sendiri
        $existingSlug = MenuCategory::where('slug', $validated['slug'])->where('id', '!=', $category->id)->first();
        if ($existingSlug) {
            // Jika slug sudah ada dan bukan milik kategori ini, tambahkan suffix
            $baseSlug = Str::slug($validated['name']);
            $count = 2;
            $newSlug = $baseSlug;
            while (MenuCategory::where('slug', $newSlug)->where('id', '!=', $category->id)->exists()) {
                $newSlug = $baseSlug . '-' . $count++;
            }
            $validated['slug'] = $newSlug;
        }


        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori menu berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuCategory $category)
    {
        if ($category->menuItems()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki item menu. Hapus item menu terlebih dahulu atau pindahkan ke kategori lain.');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori menu berhasil dihapus!');
    }
}
