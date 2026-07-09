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
            $blocks = [
                [
                    'type' => 'home_hero',
                    'data' => [
                        'hero_bg' => '',
                        'badge' => 'Selamat Datang',
                        'title' => "Bangun Website\nImpian Anda",
                        'subtitle' => 'Platform CMS profesional yang dirancang untuk membantu Anda membangun dan mengelola website dengan mudah dan cepat.',
                        'cta_primary_text' => 'Mulai Sekarang',
                        'cta_primary_link' => '/product',
                        'cta_secondary_text' => 'Pelajari Lebih Lanjut',
                        'cta_secondary_link' => '/post',
                        'stats' => [
                            ['number' => '10+', 'label' => 'Tahun Pengalaman'],
                            ['number' => '500+', 'label' => 'Klien Puas'],
                            ['number' => '99%', 'label' => 'Uptime'],
                        ]
                    ]
                ],
                [
                    'type' => 'home_categories',
                    'data' => [
                        'title' => 'Jelajahi Kategori',
                        'category_ids' => ''
                    ]
                ],
                [
                    'type' => 'home_products',
                    'data' => [
                        'title' => 'Produk Unggulan',
                        'subtitle' => 'Temukan berbagai produk dan layanan terbaik kami.',
                        'limit' => '8'
                    ]
                ],
                [
                    'type' => 'home_value_props',
                    'data' => [
                        'subtitle' => 'Mengapa Memilih Kami',
                        'title' => 'Kenapa Kami?',
                        'props' => [
                            ['title' => 'Kualitas Terbaik', 'desc' => 'Kami menggunakan teknologi dan standar terbaik untuk memberikan hasil yang optimal bagi Anda.'],
                            ['title' => 'Harga Terjangkau', 'desc' => 'Nikmati layanan berkualitas dengan harga yang kompetitif dan transparan tanpa biaya tersembunyi.'],
                            ['title' => 'Dukungan 24/7', 'desc' => 'Tim support profesional kami siap membantu Anda kapan pun Anda membutuhkannya.'],
                        ]
                    ]
                ],
                [
                    'type' => 'home_testimonials',
                    'data' => [
                        'title' => 'Apa Kata Pelanggan Kami',
                        'testimonials' => [
                            ['name' => 'Ahmad Fauzi', 'role' => 'Pengusaha', 'text' => 'Platform ini sangat membantu bisnis saya berkembang pesat!'],
                            ['name' => 'Dewi Sartika', 'role' => 'Content Creator', 'text' => 'Sangat mudah digunakan, fiturnya lengkap dan supportnya cepat.'],
                            ['name' => 'Budi Santoso', 'role' => 'Owner Toko Online', 'text' => 'CMS terbaik yang pernah saya gunakan. Sangat direkomendasikan!'],
                        ]
                    ]
                ],
                [
                    'type' => 'home_news',
                    'data' => [
                        'title' => 'Artikel Terbaru',
                        'limit' => '3'
                    ]
                ],
                [
                    'type' => 'home_cta',
                    'data' => [
                        'title' => 'Tetap Terhubung Dengan Kami',
                        'subtitle' => 'Berlangganan newsletter kami untuk mendapatkan informasi terbaru, tips, dan penawaran menarik.',
                        'button_text' => 'Berlangganan'
                    ]
                ]
            ];

            $homePage = Page::create([
                'title'    => 'Home',
                'slug'     => '__homepage__',
                'content'  => json_encode(['blocks' => $blocks]),
                'status'   => 'published',
                'template' => 'block',
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
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_schema' => 'nullable|string',
        ]);

        $data = $request->all();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        $page = Page::create($data);

        return redirect()->route('superuser.pages.edit', $page)->with('status', 'Page created successfully.');
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
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_schema' => 'nullable|string',
        ]);

        $data = $request->all();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Protect homepage slug
        if ($page->slug === '__homepage__') {
            $data['slug'] = '__homepage__';
        }

        // (Homepage custom old template logic removed since it uses Block Editor)


        $page->update($data);

        return redirect()->route('superuser.pages.edit', $page)->with('status', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        if ($page->slug === '__homepage__') {
            return redirect()->route('superuser.pages.index')->with('error', 'Cannot delete homepage.');
        }
        $page->delete();
        return redirect()->route('superuser.pages.index')->with('status', 'Page deleted successfully.');
    }

    public function builder(Page $page)
    {
        $products = \App\Models\Product::latest()->take(8)->get();
        return view('dashboard.pages.builder', compact('page', 'products'));
    }

    public function saveBuilder(Request $request, Page $page)
    {
        $page->update([
            'content' => $request->input('html'),
            'css' => $request->input('css'),
            'builder_data' => $request->input('builder_data'),
        ]);

        return response()->json(['message' => 'Page successfully saved.']);
    }
}
