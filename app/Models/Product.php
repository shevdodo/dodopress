<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'weight',
        'stock',
        'sizes',
        'image',
        'images',
        'status',
        'category_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_schema',
        'rating',
        'review_count',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
