<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        // Auto-create homepage if it doesn't exist
        $homePage = Page::where('slug', '__homepage__')->first();
        if (!$homePage) {
            $homePage = Page::create([
                'title'    => 'Home',
                'slug'     => '__homepage__',
                'content'  => json_encode([
                    'hero_badge'    => 'Selamat Datang',
                    'hero_title'    => "Bangun Website\nImpian Anda",
                    'hero_subtitle' => 'Platform CMS profesional yang dirancang untuk membantu Anda membangun dan mengelola website dengan mudah dan cepat.',
                    'hero_cta_primary'   => 'Mulai Sekarang',
                    'hero_cta_secondary' => 'Pelajari Lebih Lanjut',
                    'categories_title'   => 'Jelajahi Kategori',
                    'products_title'     => 'Produk Unggulan',
                    'products_subtitle'  => 'Temukan berbagai produk dan layanan terbaik kami.',
                    'vp_title_1' => 'Kualitas Terbaik',
                    'vp_desc_1'  => 'Kami menggunakan teknologi dan standar terbaik untuk memberikan hasil yang optimal bagi Anda.',
                    'vp_title_2' => 'Harga Terjangkau',
                    'vp_desc_2'  => 'Nikmati layanan berkualitas dengan harga yang kompetitif dan transparan tanpa biaya tersembunyi.',
                    'vp_title_3' => 'Dukungan 24/7',
                    'vp_desc_3'  => 'Tim support profesional kami siap membantu Anda kapan pun Anda membutuhkannya.',
                ]),
                'status'   => 'published',
                'template' => 'homepage',
                'order'    => 0,
            ]);
        }
        $pages = Page::where('slug', '!=', '__homepage__')->with(['category', 'parent'])->orderBy('order')->latest()->paginate(10);
        return view('dashboard.pages.index', compact('pages', 'homePage'));
    }

    public function create()
    {
        $categories = Category::where('type', 'page')->get();
        $parentPages = Page::whereNull('parent_id')->get();
        return view('dashboard.pages.create', compact('categories', 'parentPages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'nullable|string',
            'status' => 'required|in:published,draft',
            'category_id' => 'nullable|exists:categories,id',
            'parent_id' => 'nullable|exists:pages,id',
            'template' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $data = $request->all();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        Page::create($data);

        return redirect()->route('superuser.pages.index')->with('status', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        $categories = Category::where('type', 'page')->get();
        $parentPages = Page::where('id', '!=', $page->id)->whereNull('parent_id')->get();
        $productCategories = \App\Models\Category::where('type', 'product')->orderBy('name')->get();
        return view('dashboard.pages.edit', compact('page', 'categories', 'parentPages', 'productCategories'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'nullable|string',
            'status' => 'required|in:published,draft',
            'category_id' => 'nullable|exists:categories,id',
            'parent_id' => 'nullable|exists:pages,id',
            'template' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $data = $request->all();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Handle homepage structured content
        if ($page->template === 'homepage' && $request->has('hp')) {
            $hp = $request->input('hp');

            // Retrieve existing background image if not replaced
            $currentHpContent = [];
            if (!empty($page->content)) {
                $decoded = json_decode($page->content, true);
                if (json_last_error() === JSON_ERROR_NONE) $currentHpContent = $decoded;
            }

            $heroBg = $currentHpContent['hero_bg'] ?? null;
            if ($request->hasFile('hero_bg')) {
                $heroBg = $request->file('hero_bg')->store('media/' . date('Y/m'), 'public');
            } elseif ($request->has('hero_bg_media_path')) {
                $heroBg = $request->input('hero_bg_media_path');
            }
            $hp['hero_bg'] = $heroBg;

            $data['content'] = json_encode($hp);
            $data['template'] = 'homepage';
            $data['status'] = 'published';
            $data['slug'] = '__homepage__';
        }

        $page->update($data);

        return redirect()->route('superuser.pages.index')->with('status', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('superuser.pages.index')->with('status', 'Page deleted successfully.');
    }
}
