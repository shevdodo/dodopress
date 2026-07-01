<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with('parent')->latest();
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $categories = $query->paginate(10)->appends($request->query());
        return view('dashboard.categories.index', compact('categories'));
    }

    public function create(Request $request)
    {
        $parentCategories = Category::when($request->has('type'), function($q) use ($request) {
            $q->where('type', $request->type);
        })->whereNull('parent_id')->orderBy('name')->get();
        return view('dashboard.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'type' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['image']);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        } elseif ($request->has('image_media_path')) {
            $data['image'] = $request->input('image_media_path');
        }

        Category::create($data);

        return redirect()->route('superuser.categories.index', ['type' => $data['type'] ?? 'post'])->with('status', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::where('type', $category->type)
            ->where('id', '!=', $category->id)
            ->orderBy('name')->get();
        return view('dashboard.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'type' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['image']);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        } elseif ($request->has('image_media_path')) {
            $data['image'] = $request->input('image_media_path');
        }

        $category->update($data);

        return redirect()->route('superuser.categories.index', ['type' => $data['type'] ?? 'post'])->with('status', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('superuser.categories.index', ['type' => $category->type ?? 'post'])->with('status', 'Category deleted successfully.');
    }
}
