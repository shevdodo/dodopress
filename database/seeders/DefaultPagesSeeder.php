<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultPagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title'   => 'Home',
                'slug'    => '__homepage__',
                'content' => json_encode([
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
            ],
            [
                'title'   => 'Store',
                'slug'    => 'store',
                'content' => '<h1>Our Store</h1><p>Browse our collection of products. You can edit this content from the admin dashboard.</p>',
                'status'  => 'published',
                'template'=> 'default',
                'order'   => 2,
            ],
            [
                'title'   => 'About Us',
                'slug'    => 'about-us',
                'content' => '<h1>About Us</h1><p>We are a passionate team dedicated to delivering the best products and services. You can edit this content from the admin dashboard.</p>',
                'status'  => 'published',
                'template'=> 'default',
                'order'   => 3,
            ],
            [
                'title'   => 'Contact Us',
                'slug'    => 'contact-us',
                'content' => '<h1>Contact Us</h1><p>We would love to hear from you! You can edit this content from the admin dashboard.</p>',
                'status'  => 'published',
                'template'=> 'default',
                'order'   => 4,
            ],
        ];

        foreach ($pages as $page) {
            DB::table('pages')->updateOrInsert(
                ['slug' => $page['slug']],
                array_merge($page, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
