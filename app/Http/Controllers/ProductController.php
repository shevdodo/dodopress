<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->latest();
        
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }
        
        $products = $query->paginate(10)->appends($request->all());
        $categories = Category::where('type', 'product')->get();
        
        return view('dashboard.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('type', 'product')->orderBy('name')->get();
        return view('dashboard.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'price'       => 'nullable|numeric|min:0',
            'weight'      => 'nullable|integer|min:0',
            'stock'       => 'nullable|integer|min:0',
            'sizes'       => 'nullable|string|max:255',
            'status'      => 'required|in:available,unavailable',
            'category_id' => 'nullable|exists:categories,id',
            'image'       => 'nullable|image|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_schema' => 'nullable|string',
            'rating'      => 'nullable|numeric|min:0|max:5',
            'review_count'=> 'nullable|integer|min:0',
            'shopee_link' => 'nullable|string|max:500',
            'is_preorder' => 'nullable|boolean',
        ]);

        $data = $request->except(['image']);
        $data['is_preorder'] = $request->has('is_preorder');
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        if (empty($data['price'])) {
            $data['price'] = 0;
        }

        if (!isset($data['stock']) || $data['stock'] === '') {
            $data['stock'] = 0;
        }

        if ($request->hasFile('image')) {
            $folder = 'media/' . date('Y/m');
            $file = $request->file('image');
            $safeName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $data['image'] = $file->storeAs($folder, $safeName, 'public');
        } elseif ($request->has('image_media_path')) {
            $data['image'] = $request->input('image_media_path');
        }

        $images = [];
        if ($request->hasFile('gallery_images')) {
            $folder = 'media/' . date('Y/m');
            foreach ($request->file('gallery_images') as $file) {
                $isImage = str_starts_with($file->getMimeType(), 'image/') && $file->getMimeType() !== 'image/svg+xml';
                
                if ($isImage) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeName = Str::slug($originalName) . '-' . uniqid() . '.webp';
                    $path = $folder . '/' . $safeName;
                    
                    $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
                    $image = $manager->read($file->getRealPath());
                    $encoded = $image->toWebp(80);
                    
                    Storage::disk('public')->put($path, $encoded->toString());
                    $images[] = $path;
                } else {
                    $safeName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $images[] = $file->storeAs($folder, $safeName, 'public');
                }
            }
        }
        $data['images'] = $images;

        $product = Product::create($data);

        return redirect()->route('superuser.products.edit', $product)->with('status', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('type', 'product')->orderBy('name')->get();
        return view('dashboard.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'nullable|string',
            'price'       => 'nullable|numeric|min:0',
            'weight'      => 'nullable|integer|min:0',
            'stock'       => 'nullable|integer|min:0',
            'sizes'       => 'nullable|string|max:255',
            'status'      => 'required|in:available,unavailable',
            'category_id' => 'nullable|exists:categories,id',
            'image'       => 'nullable|image|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_schema' => 'nullable|string',
            'rating'      => 'nullable|numeric|min:0|max:5',
            'review_count'=> 'nullable|integer|min:0',
            'shopee_link' => 'nullable|string|max:500',
            'is_preorder' => 'nullable|boolean',
        ]);

        $data = $request->except(['image']);
        $data['is_preorder'] = $request->has('is_preorder');
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if (empty($data['price'])) {
            $data['price'] = 0;
        }

        if (!isset($data['stock']) || $data['stock'] === '') {
            $data['stock'] = 0;
        }

        if ($request->hasFile('image')) {
            $folder = 'media/' . date('Y/m');
            $file = $request->file('image');
            $safeName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $data['image'] = $file->storeAs($folder, $safeName, 'public');
        } elseif ($request->has('image_media_path')) {
            $data['image'] = $request->input('image_media_path');
        }

        $existingGallery = $request->input('existing_gallery', []);
        $deleteGallery = $request->input('delete_gallery', []);
        
        $images = [];
        foreach ($existingGallery as $idx => $img) {
            // Check if this index is marked for deletion
            if (!in_array($idx, $deleteGallery)) {
                $images[] = $img;
            } else {
                // Optionally delete the file from storage
                // Storage::disk('public')->delete($img);
            }
        }
        
        if ($request->hasFile('gallery_images')) {
            $folder = 'media/' . date('Y/m');
            foreach ($request->file('gallery_images') as $file) {
                $isImage = str_starts_with($file->getMimeType(), 'image/') && $file->getMimeType() !== 'image/svg+xml';
                
                if ($isImage) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeName = Str::slug($originalName) . '-' . uniqid() . '.webp';
                    $path = $folder . '/' . $safeName;
                    
                    $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
                    $image = $manager->read($file->getRealPath());
                    $encoded = $image->toWebp(80);
                    
                    Storage::disk('public')->put($path, $encoded->toString());
                    $images[] = $path;
                } else {
                    $safeName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $images[] = $file->storeAs($folder, $safeName, 'public');
                }
            }
        }
        $data['images'] = $images;

        $product->update($data);

        return redirect()->route('superuser.products.edit', $product)->with('status', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('superuser.products.index')->with('status', 'Product deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->route('superuser.products.index')->with('status', 'No products selected.');
        }
        $count = Product::whereIn('id', $ids)->count();
        Product::whereIn('id', $ids)->delete();
        return redirect()->route('superuser.products.index')->with('status', "{$count} product(s) deleted successfully.");
    }

    public function bulkUpdate(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->route('superuser.products.index')->with('status', 'No products selected.');
        }

        $updateData = [];
        if ($request->filled('bulk_category_id')) {
            $updateData['category_id'] = $request->input('bulk_category_id');
        }
        if ($request->filled('bulk_status')) {
            $updateData['status'] = $request->input('bulk_status');
        }
        if ($request->filled('bulk_price')) {
            $updateData['price'] = $request->input('bulk_price');
        }
        if ($request->filled('bulk_stock')) {
            $updateData['stock'] = $request->input('bulk_stock');
        }

        if (empty($updateData)) {
            return redirect()->route('superuser.products.index')->with('status', 'No changes specified for bulk edit.');
        }

        $count = Product::whereIn('id', $ids)->update($updateData);
        return redirect()->route('superuser.products.index')->with('status', "{$count} product(s) updated successfully.");
    }

    public function export(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ProductsExport($search, $categoryId), 'products-' . date('Y-m-d-H-i-s') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,csv'
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\ProductsImport, $request->file('import_file'));
            return redirect()->route('superuser.products.index')->with('status', 'Products imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('superuser.products.index')->with('status', 'Error importing products: ' . $e->getMessage());
        }
    }
}
