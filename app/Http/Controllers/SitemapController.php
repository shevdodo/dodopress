<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\Setting;

class SitemapController extends Controller
{
    private function checkEnabled()
    {
        $enabled = Setting::where('key', 'sitemap_enabled')->value('value');
        if ($enabled === '0') {
            abort(404);
        }
    }

    private function secureUrl($path)
    {
        $url = url($path);
        // Force HTTPS if request is secure, behind proxy, or on the live domain
        if (request()->secure() || request()->header('x-forwarded-proto') === 'https' || str_contains($url, 'batikmukti.co.id')) {
            $url = str_replace('http://', 'https://', $url);
        }
        return $url;
    }

    private function renderXml($content)
    {
        // Use relative path for XSL to avoid Mixed Content Block by browsers
        return response('<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="/sitemap.xsl"?>' . "\n" . $content, 200)
            ->header('Content-Type', 'text/xml');
    }

    public function index()
    {
        $this->checkEnabled();

        $latestPage = Page::where('status', 'published')->latest('updated_at')->first();
        $latestPost = Post::where('status', 'published')->latest('updated_at')->first();
        $latestProduct = Product::where('status', 'available')->latest('updated_at')->first();

        $content = '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $content .= '<sitemap>';
        $content .= '<loc>' . $this->secureUrl('/page-sitemap.xml') . '</loc>';
        if ($latestPage) $content .= '<lastmod>' . $latestPage->updated_at->toAtomString() . '</lastmod>';
        $content .= '</sitemap>';

        $content .= '<sitemap>';
        $content .= '<loc>' . $this->secureUrl('/product-sitemap.xml') . '</loc>';
        if ($latestProduct) $content .= '<lastmod>' . $latestProduct->updated_at->toAtomString() . '</lastmod>';
        $content .= '</sitemap>';

        $content .= '<sitemap>';
        $content .= '<loc>' . $this->secureUrl('/post-sitemap.xml') . '</loc>';
        if ($latestPost) $content .= '<lastmod>' . $latestPost->updated_at->toAtomString() . '</lastmod>';
        $content .= '</sitemap>';

        $content .= '</sitemapindex>';

        return $this->renderXml($content);
    }

    public function pages()
    {
        $this->checkEnabled();
        $pages = Page::where('status', 'published')->get();

        $content = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        $content .= '<url>';
        $content .= '<loc>' . $this->secureUrl('/') . '</loc>';
        $content .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
        $content .= '<changefreq>daily</changefreq>';
        $content .= '<priority>1.0</priority>';
        $content .= '</url>';

        foreach ($pages as $page) {
            if ($page->slug === '__homepage__') continue;
            $content .= '<url>';
            $content .= '<loc>' . $this->secureUrl('/' . $page->slug) . '</loc>';
            $content .= '<lastmod>' . $page->updated_at->toAtomString() . '</lastmod>';
            $content .= '<changefreq>weekly</changefreq>';
            $content .= '<priority>0.8</priority>';
            $content .= '</url>';
        }
        $content .= '</urlset>';

        return $this->renderXml($content);
    }

    public function products()
    {
        $this->checkEnabled();
        $products = Product::where('status', 'available')->get();
        $productBase = Setting::where('key', 'product_permalink_base')->value('value') ?: 'store';

        $content = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $content .= '<url>';
        $content .= '<loc>' . $this->secureUrl('/' . $productBase) . '</loc>';
        $content .= '<changefreq>daily</changefreq>';
        $content .= '<priority>0.9</priority>';
        $content .= '</url>';

        foreach ($products as $product) {
            $catSlug = $product->category ? $product->category->slug : 'uncategorized';
            $content .= '<url>';
            $content .= '<loc>' . $this->secureUrl('/' . $productBase . '/' . $catSlug . '/' . $product->slug) . '</loc>';
            $content .= '<lastmod>' . $product->updated_at->toAtomString() . '</lastmod>';
            $content .= '<changefreq>weekly</changefreq>';
            $content .= '<priority>0.8</priority>';
            $content .= '</url>';
        }
        $content .= '</urlset>';

        return $this->renderXml($content);
    }

    public function posts()
    {
        $this->checkEnabled();
        $posts = Post::where('status', 'published')->get();
        $postBase = Setting::where('key', 'post_permalink_base')->value('value') ?: 'blog';

        $content = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $content .= '<url>';
        $content .= '<loc>' . $this->secureUrl('/' . $postBase) . '</loc>';
        $content .= '<changefreq>daily</changefreq>';
        $content .= '<priority>0.9</priority>';
        $content .= '</url>';

        foreach ($posts as $post) {
            $catSlug = $post->category ? $post->category->slug : 'uncategorized';
            $content .= '<url>';
            $content .= '<loc>' . $this->secureUrl('/' . $postBase . '/' . $catSlug . '/' . $post->slug) . '</loc>';
            $content .= '<lastmod>' . $post->updated_at->toAtomString() . '</lastmod>';
            $content .= '<changefreq>weekly</changefreq>';
            $content .= '<priority>0.7</priority>';
            $content .= '</url>';
        }
        $content .= '</urlset>';

        return $this->renderXml($content);
    }
}
