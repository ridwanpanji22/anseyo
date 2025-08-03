<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('menus')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15);
        
        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => true,
        ];

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => true,
        ];

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(Category $category)
    {
        // Check if category has menus
        if ($category->menus()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki menu');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}
