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
                    'blocks' => [
                        [
                            'id' => 'hero_1',
                            'type' => 'home_hero',
                            'data' => [
                                'badge' => 'Selamat Datang',
                                'title' => "Bangun Website\nImpian Anda",
                                'subtitle' => 'Platform CMS profesional yang dirancang untuk membantu Anda membangun dan mengelola website dengan mudah dan cepat.',
                                'cta_primary_text' => 'Mulai Sekarang',
                                'cta_primary_link' => '/products',
                                'cta_secondary_text' => 'Pelajari Lebih Lanjut',
                                'cta_secondary_link' => '/posts',
                                'stats' => [
                                    ['number' => '10+', 'label' => 'Tahun Pengalaman'],
                                    ['number' => '500+', 'label' => 'Klien Puas'],
                                    ['number' => '99%', 'label' => 'Uptime'],
                                ]
                            ]
                        ],
                        [
                            'id' => 'cat_1',
                            'type' => 'home_categories',
                            'data' => [
                                'title' => 'Jelajahi Kategori',
                                'category_ids' => ''
                            ]
                        ]
                    ]
                ]),
                'status'   => 'published',
                'template' => 'block',
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
                'content' => json_encode([
                    'hero_badge'    => 'Hubungi Kami',
                    'hero_title'    => 'Get In Touch',
                    'hero_subtitle' => 'Kami siap membantu Anda. Silakan hubungi kami melalui form di bawah atau informasi kontak yang tersedia.',
                    'info_title_1' => 'Alamat',
                    'info_desc_1'  => 'Jl. Contoh No. 123, Kota Contoh, Provinsi 12345',
                    'info_title_2' => 'Telepon',
                    'info_desc_2'  => '+62 812-3456-7890',
                    'info_title_3' => 'Email',
                    'info_desc_3'  => 'info@example.com',
                    'info_title_4' => 'Jam Operasional',
                    'info_desc_4'  => 'Sen - Sab: 08:00 - 17:00',
                    'form_title'     => 'Kirim Pesan',
                    'form_subtitle'  => 'Isi form di bawah dan tim kami akan menghubungi Anda segera.',
                    'form_button'    => 'Kirim Pesan',
                    'map_title'      => 'Lokasi Kami',
                    'social_title'   => 'Ikuti Kami',
                    'social_facebook'  => '#',
                    'social_instagram' => '#',
                    'social_twitter'   => '#',
                    'social_youtube'   => '#',
                ]),
                'status'   => 'published',
                'template' => 'contact',
                'order'    => 4,
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
