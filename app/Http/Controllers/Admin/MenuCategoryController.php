<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Http\Requests\Admin\StoreMenuCategoryRequest;
use App\Http\Requests\Admin\UpdateMenuCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

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
        // ... (logika slug Anda) ...
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        $count = MenuCategory::where('slug', $validated['slug'])->count();
        if ($count > 0) {
            $validated['slug'] = $validated['slug'] . '-' . ($count + 1);
        }

        MenuCategory::create($validated);

        Alert::success('Berhasil!', 'Kategori menu berhasil ditambahkan.');
        return redirect()->route('admin.categories.index');
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
        // ... (logika slug Anda) ...
        if (empty($validated['slug']) && $request->filled('name')) {
            $validated['slug'] = Str::slug($validated['name']);
        } elseif ($category->name !== $request->name && $request->slug === $category->slug && !$request->filled('slug_manually_edited')) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if (isset($validated['slug'])) {
            $existingSlug = MenuCategory::where('slug', $validated['slug'])->where('id', '!=', $category->id)->first();
            if ($existingSlug) {
                $baseSlug = Str::slug($request->name);
                $count = 2;
                $newSlug = $baseSlug;
                while (MenuCategory::where('slug', $newSlug)->where('id', '!=', $category->id)->exists()) {
                    $newSlug = $baseSlug . '-' . $count++;
                }
                $validated['slug'] = $newSlug;
            }
        }

        $category->update($validated);

        Alert::success('Berhasil!', 'Kategori menu berhasil diperbarui.'); // Pesan SweetAlert
        return redirect()->route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuCategory $category)
    {
        if ($category->menuItems()->count() > 0) {
            Alert::error('Gagal!', 'Kategori tidak dapat dihapus karena masih memiliki item menu.'); // Pesan SweetAlert
            return redirect()->route('admin.categories.index');
        }

        $category->delete();
        Alert::success('Berhasil!', 'Kategori menu berhasil dihapus.');
        return redirect()->route('admin.categories.index');
    }
}
