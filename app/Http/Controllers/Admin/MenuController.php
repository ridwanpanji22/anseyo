<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $menus = Menu::with('category')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10);
            
        return view('admin.menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::active()->ordered()->get();
        return view('admin.menu.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['is_available'] = $request->has('is_available');
        $data['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menus', 'public');
            $data['image'] = $imagePath;
        }

        Menu::create($data);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu): View
    {
        $menu->load('category');
        return view('admin.menu.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu): View
    {
        $categories = Category::active()->ordered()->get();
        return view('admin.menu.edit', compact('menu', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['is_available'] = $request->has('is_available');
        $data['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($menu->image) {
                \Storage::disk('public')->delete($menu->image);
            }
            
            $imagePath = $request->file('image')->store('menus', 'public');
            $data['image'] = $imagePath;
        }

        $menu->update($data);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        // Delete image if exists
        if ($menu->image) {
            \Storage::disk('public')->delete($menu->image);
        }
        
        $menu->delete();

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil dihapus');
    }
}
