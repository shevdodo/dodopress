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
                    'hero_badge'    => 'Koleksi Eksklusif 2024',
                    'hero_title'    => "Keanggunan Tradisi\ndalam Balutan Modern",
                    'hero_subtitle' => 'Temukan koleksi batik terbaik yang dirancang khusus untuk menyempurnakan gaya Anda di setiap momen istimewa.',
                    'hero_cta_primary'   => 'Belanja Sekarang',
                    'hero_cta_secondary' => 'Lihat Jurnal',
                    'categories_title'   => 'Kategori Pilihan',
                    'products_title'     => 'Produk Unggulan',
                    'products_subtitle'  => 'Temukan koleksi batik pilihan kami yang dibuat dengan penuh keahlian dan cinta.',
                    'vp_title_1' => 'Kualitas Premium',
                    'vp_desc_1'  => 'Dibuat dari bahan pilihan dengan teknik membatik terbaik yang diwariskan turun-temurun.',
                    'vp_title_2' => 'Harga Bersaing',
                    'vp_desc_2'  => 'Dapatkan koleksi batik eksklusif dengan harga yang sesuai dengan kualitas premium yang kami tawarkan.',
                    'vp_title_3' => 'Layanan Cepat',
                    'vp_desc_3'  => 'Tim kami siap membantu Anda dengan layanan responsif untuk setiap pertanyaan dan pemesanan.',
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
