<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = ['menu_id', 'title', 'url', 'target', 'type', 'reference_id', 'parent_id', 'order'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    public function getResolvedUrlAttribute()
    {
        if ($this->type === 'custom') {
            return $this->url;
        }

        if ($this->type === 'page') {
            $page = Page::find($this->reference_id);
            if (!$page) return '#';
            // Home page resolves to root /
            if ($page->slug === 'home' || $page->template === 'homepage') return url('/');
            return url('/' . $page->slug);
        }

        if ($this->type === 'category') {
            $category = Category::find($this->reference_id);
            if ($category) {
                if ($category->type === 'product') {
                    $base = \App\Models\Setting::where('key', 'product_permalink_base')->value('value') ?: 'store';
                    return url('/' . $base . '/' . $category->slug);
                } else {
                    $base = \App\Models\Setting::where('key', 'post_permalink_base')->value('value') ?: 'blog';
                    return url('/' . $base . '/' . $category->slug);
                }
            }
            return '#';
        }

        return '#';
    }
}
