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
                'slug'    => 'home',
                'content' => '<h1>Welcome to Our Website</h1><p>This is the home page. You can edit this content from the admin dashboard.</p>',
                'status'  => 'published',
                'template'=> 'default',
                'order'   => 1,
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
