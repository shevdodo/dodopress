<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $name = $row['nama_produk'] ?? null;
        if (!$name) return null;

        // Cek apakah produk sudah ada
        $product = Product::where('slug', Str::slug($name))->first();

        $categoryName = $row['kategori'] ?? null;
        $categoryId = null;
        if ($categoryName) {
            $category = \App\Models\Category::firstOrCreate(
                ['name' => $categoryName, 'type' => 'product'],
                ['slug' => Str::slug($categoryName)]
            );
            $categoryId = $category->id;
        }

        if ($product) {
            // Update jika ada
            $product->update([
                'price' => $row['harga'] ?? $product->price,
                'weight' => $row['berat_g'] ?? $product->weight,
                'stock' => $row['stok'] ?? $product->stock,
                'sizes' => $row['ukuran'] ?? $product->sizes,
                'status' => (isset($row['status']) && strtolower($row['status']) == 'aktif') ? 'available' : 'unavailable',
                'category_id' => $categoryId ?? $product->category_id,
            ]);
            return null; // Mengembalikan null karena sudah diupdate
        }

        // Buat baru jika tidak ada
        return new Product([
            'name' => $name,
            'slug' => Str::slug($name),
            'price' => $row['harga'] ?? 0,
            'weight' => $row['berat_g'] ?? 0,
            'stock' => $row['stok'] ?? 0,
            'sizes' => $row['ukuran'] ?? null,
            'status' => (isset($row['status']) && strtolower($row['status']) == 'aktif') ? 'available' : 'unavailable',
            'category_id' => $categoryId,
        ]);
    }
}
