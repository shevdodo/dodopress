<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\Setting;

class SitemapController extends Controller
{
    public function index()
    {
        $enabled = Setting::where('key', 'sitemap_enabled')->value('value');
        if ($enabled === '0') {
            abort(404);
        }

        $pages = Page::where('status', 'published')->get();
        $posts = Post::where('status', 'published')->get();
        $products = Product::where('status', 'available')->get();

        $postBase = Setting::where('key', 'post_permalink_base')->value('value') ?: 'blog';
        $productBase = Setting::where('key', 'product_permalink_base')->value('value') ?: 'store';

        $sitemapContent = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemapContent .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Homepage
        $sitemapContent .= '<url>';
        $sitemapContent .= '<loc>' . url('/') . '</loc>';
        $sitemapContent .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
        $sitemapContent .= '<changefreq>daily</changefreq>';
        $sitemapContent .= '<priority>1.0</priority>';
        $sitemapContent .= '</url>';

        // Pages
        foreach ($pages as $page) {
            if ($page->slug === '__homepage__') continue;
            
            $sitemapContent .= '<url>';
            $sitemapContent .= '<loc>' . url('/' . $page->slug) . '</loc>';
            $sitemapContent .= '<lastmod>' . $page->updated_at->toAtomString() . '</lastmod>';
            $sitemapContent .= '<changefreq>weekly</changefreq>';
            $sitemapContent .= '<priority>0.8</priority>';
            $sitemapContent .= '</url>';
        }

        // Store Index
        $sitemapContent .= '<url>';
        $sitemapContent .= '<loc>' . url('/' . $productBase) . '</loc>';
        $sitemapContent .= '<changefreq>daily</changefreq>';
        $sitemapContent .= '<priority>0.9</priority>';
        $sitemapContent .= '</url>';

        // Products
        foreach ($products as $product) {
            $catSlug = $product->category ? $product->category->slug : 'uncategorized';
            $sitemapContent .= '<url>';
            $sitemapContent .= '<loc>' . url('/' . $productBase . '/' . $catSlug . '/' . $product->slug) . '</loc>';
            $sitemapContent .= '<lastmod>' . $product->updated_at->toAtomString() . '</lastmod>';
            $sitemapContent .= '<changefreq>weekly</changefreq>';
            $sitemapContent .= '<priority>0.8</priority>';
            $sitemapContent .= '</url>';
        }

        // Blog Index
        $sitemapContent .= '<url>';
        $sitemapContent .= '<loc>' . url('/' . $postBase) . '</loc>';
        $sitemapContent .= '<changefreq>daily</changefreq>';
        $sitemapContent .= '<priority>0.9</priority>';
        $sitemapContent .= '</url>';

        // Posts
        foreach ($posts as $post) {
            $catSlug = $post->category ? $post->category->slug : 'uncategorized';
            $sitemapContent .= '<url>';
            $sitemapContent .= '<loc>' . url('/' . $postBase . '/' . $catSlug . '/' . $post->slug) . '</loc>';
            $sitemapContent .= '<lastmod>' . $post->updated_at->toAtomString() . '</lastmod>';
            $sitemapContent .= '<changefreq>weekly</changefreq>';
            $sitemapContent .= '<priority>0.7</priority>';
            $sitemapContent .= '</url>';
        }

        $sitemapContent .= '</urlset>';

        return response($sitemapContent, 200)
            ->header('Content-Type', 'text/xml');
    }
}
