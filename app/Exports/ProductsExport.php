<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::with('category')->latest()->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Produk',
            'Slug',
            'Kategori',
            'Harga',
            'Berat (g)',
            'Stok',
            'Ukuran',
            'Status',
            'Tanggal Dibuat',
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->slug,
            $product->category ? $product->category->name : 'Tanpa Kategori',
            $product->price,
            $product->weight,
            $product->stock,
            $product->sizes,
            $product->status == 'available' ? 'Aktif' : 'Tidak Aktif',
            $product->created_at ? $product->created_at->format('Y-m-d H:i:s') : '',
        ];
    }
}
