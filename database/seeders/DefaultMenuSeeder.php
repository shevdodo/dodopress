<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update the Main Menu
        $menuId = DB::table('menus')->where('location', 'main')->value('id');

        if (!$menuId) {
            $menuId = DB::table('menus')->insertGetId([
                'name'       => 'Main Menu',
                'location'   => 'main',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Get page IDs from DB
        $pages = DB::table('pages')
            ->whereIn('slug', ['home', 'store', 'about-us', 'contact-us'])
            ->pluck('id', 'slug');

        $items = [
            [
                'title'        => 'Home',
                'url'          => '/home',
                'type'         => 'page',
                'reference_id' => $pages['home'] ?? null,
                'order'        => 1,
            ],
            [
                'title'        => 'Store',
                'url'          => '/store',
                'type'         => 'page',
                'reference_id' => $pages['store'] ?? null,
                'order'        => 2,
            ],
            [
                'title'        => 'About Us',
                'url'          => '/about-us',
                'type'         => 'page',
                'reference_id' => $pages['about-us'] ?? null,
                'order'        => 3,
            ],
            [
                'title'        => 'Contact Us',
                'url'          => '/contact-us',
                'type'         => 'page',
                'reference_id' => $pages['contact-us'] ?? null,
                'order'        => 4,
            ],
        ];

        // Clear existing items for this menu to avoid duplicates
        DB::table('menu_items')->where('menu_id', $menuId)->delete();

        foreach ($items as $item) {
            DB::table('menu_items')->insert(array_merge($item, [
                'menu_id'    => $menuId,
                'target'     => '_self',
                'parent_id'  => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
