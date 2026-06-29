<?php

return array (
  'blocks' => 
  array (
    'hero' => 
    array (
      'name' => 'Hero Section',
      'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
      'component' => 'components.blocks.hero',
      'fields' => 
      array (
        'badge' => 
        array (
          'type' => 'text',
          'label' => 'Badge Text',
        ),
        'title' => 
        array (
          'type' => 'textarea',
          'label' => 'Judul Utama',
        ),
        'subtitle' => 
        array (
          'type' => 'textarea',
          'label' => 'Subtitle',
        ),
        'button_text' => 
        array (
          'type' => 'text',
          'label' => 'Tombol Teks',
        ),
        'button_link' => 
        array (
          'type' => 'text',
          'label' => 'Tombol Link',
        ),
        'bg_image' => 
        array (
          'type' => 'image',
          'label' => 'Gambar Latar',
        ),
      ),
      'default' => 
      array (
        'badge' => 'Selamat Datang',
        'title' => 'Bangun Website
Impian Anda',
        'subtitle' => 'Solusi digital terbaik untuk bisnis Anda.',
        'button_text' => 'Mulai Sekarang',
        'button_link' => '#',
        'bg_image' => '',
      ),
    ),
    'text' => 
    array (
      'name' => 'Text',
      'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>',
      'component' => 'components.blocks.text',
      'fields' => 
      array (
        'content' => 
        array (
          'type' => 'richtext',
          'label' => 'Konten',
        ),
      ),
      'default' => 
      array (
        'content' => '<p class="text-lg text-gray-600 leading-relaxed">Tulis konten Anda di sini...</p>',
      ),
    ),
    'image' => 
    array (
      'name' => 'Image',
      'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
      'component' => 'components.blocks.image',
      'fields' => 
      array (
        'src' => 
        array (
          'type' => 'image',
          'label' => 'Gambar',
        ),
        'alt' => 
        array (
          'type' => 'text',
          'label' => 'Alt Text',
        ),
        'caption' => 
        array (
          'type' => 'text',
          'label' => 'Caption',
        ),
      ),
      'default' => 
      array (
        'src' => '',
        'alt' => 'Image',
        'caption' => '',
      ),
    ),
    'gallery' => 
    array (
      'name' => 'Gallery',
      'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>',
      'component' => 'components.blocks.gallery',
      'fields' => 
      array (
        'images' => 
        array (
          'type' => 'gallery',
          'label' => 'Gambar',
        ),
        'columns' => 
        array (
          'type' => 'select',
          'label' => 'Kolom',
          'options' => 
          array (
            2 => '2',
            3 => '3',
            4 => '4',
          ),
        ),
      ),
      'default' => 
      array (
        'images' => 
        array (
        ),
        'columns' => 3,
      ),
    ),
    'features' => 
    array (
      'name' => 'Features',
      'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>',
      'component' => 'components.blocks.features',
      'fields' => 
      array (
        'columns' => 
        array (
          'type' => 'select',
          'label' => 'Jumlah per baris',
          'options' => 
          array (
            2 => '2',
            3 => '3',
            4 => '4',
          ),
        ),
        'items' => 
        array (
          'type' => 'repeater',
          'label' => 'Item Fitur',
          'subfields' => 
          array (
            0 => 
            array (
              'key' => 'icon',
              'type' => 'text',
              'label' => 'Icon',
            ),
            1 => 
            array (
              'key' => 'title',
              'type' => 'text',
              'label' => 'Judul',
            ),
            2 => 
            array (
              'key' => 'desc',
              'type' => 'textarea',
              'label' => 'Deskripsi',
            ),
          ),
        ),
      ),
      'default' => 
      array (
        'columns' => 3,
        'items' => 
        array (
          0 => 
          array (
            'icon' => '🚀',
            'title' => 'Cepat',
            'desc' => 'Performance tinggi.',
          ),
          1 => 
          array (
            'icon' => '🔒',
            'title' => 'Aman',
            'desc' => 'Keamanan terjamin.',
          ),
          2 => 
          array (
            'icon' => '📱',
            'title' => 'Responsif',
            'desc' => 'Tampil di semua perangkat.',
          ),
        ),
      ),
    ),
    'cta' => 
    array (
      'name' => 'Call to Action',
      'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
      'component' => 'components.blocks.cta',
      'fields' => 
      array (
        'title' => 
        array (
          'type' => 'text',
          'label' => 'Judul',
        ),
        'description' => 
        array (
          'type' => 'textarea',
          'label' => 'Deskripsi',
        ),
        'button_text' => 
        array (
          'type' => 'text',
          'label' => 'Tombol Teks',
        ),
        'button_link' => 
        array (
          'type' => 'text',
          'label' => 'Tombol Link',
        ),
        'bg_color' => 
        array (
          'type' => 'select',
          'label' => 'Warna Latar',
          'options' => 
          array (
            'brand' => 'Brand',
            'dark' => 'Dark',
            'light' => 'Light',
          ),
        ),
      ),
      'default' => 
      array (
        'title' => 'Siap Memulai?',
        'description' => 'Hubungi kami sekarang untuk konsultasi gratis.',
        'button_text' => 'Hubungi Kami',
        'button_link' => '#',
        'bg_color' => 'brand',
      ),
    ),
    'testimonial' => 
    array (
      'name' => 'Testimonial',
      'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>',
      'component' => 'components.blocks.testimonial',
      'fields' => 
      array (
        'items' => 
        array (
          'type' => 'repeater',
          'label' => 'Testimonial',
          'subfields' => 
          array (
            0 => 
            array (
              'key' => 'quote',
              'type' => 'textarea',
              'label' => 'Testimonial',
            ),
            1 => 
            array (
              'key' => 'name',
              'type' => 'text',
              'label' => 'Nama',
            ),
            2 => 
            array (
              'key' => 'role',
              'type' => 'text',
              'label' => 'Jabatan',
            ),
            3 => 
            array (
              'key' => 'avatar',
              'type' => 'image',
              'label' => 'Avatar',
            ),
          ),
        ),
      ),
      'default' => 
      array (
        'items' => 
        array (
          0 => 
          array (
            'quote' => 'Pelayanan sangat memuaskan!',
            'name' => 'John Doe',
            'role' => 'CEO Perusahaan',
            'avatar' => '',
          ),
        ),
      ),
    ),
    'faq' => 
    array (
      'name' => 'FAQ (Accordion)',
      'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>',
      'component' => 'components.blocks.faq',
      'fields' => 
      array (
        'items' => 
        array (
          'type' => 'repeater',
          'label' => 'FAQ',
          'subfields' => 
          array (
            0 => 
            array (
              'key' => 'question',
              'type' => 'text',
              'label' => 'Pertanyaan',
            ),
            1 => 
            array (
              'key' => 'answer',
              'type' => 'textarea',
              'label' => 'Jawaban',
            ),
          ),
        ),
      ),
      'default' => 
      array (
        'items' => 
        array (
          0 => 
          array (
            'question' => 'Apa itu Dodopress?',
            'answer' => 'Dodopress adalah CMS modern berbasis Laravel.',
          ),
        ),
      ),
    ),
    'video' => 
    array (
      'name' => 'Video Embed',
      'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
      'component' => 'components.blocks.video',
      'fields' => 
      array (
        'url' => 
        array (
          'type' => 'text',
          'label' => 'URL YouTube/Vimeo',
        ),
        'caption' => 
        array (
          'type' => 'text',
          'label' => 'Caption',
        ),
      ),
      'default' => 
      array (
        'url' => '',
        'caption' => '',
      ),
    ),
    'spacer' => 
    array (
      'name' => 'Spacer',
      'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"/></svg>',
      'component' => 'components.blocks.spacer',
      'fields' => 
      array (
        'height' => 
        array (
          'type' => 'select',
          'label' => 'Tinggi',
          'options' => 
          array (
            'sm' => 'Kecil',
            'md' => 'Sedang',
            'lg' => 'Besar',
            'xl' => 'Extra Besar',
          ),
        ),
      ),
      'default' => 
      array (
        'height' => 'md',
      ),
    ),
    'contact' => 
    array (
      'name' => 'Contact Page',
      'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
      'component' => 'components.blocks.contact',
      'fields' => 
      array (
        'badge' => 
        array (
          'type' => 'text',
          'label' => 'Badge Hero',
        ),
        'title' => 
        array (
          'type' => 'text',
          'label' => 'Judul Hero',
        ),
        'subtitle' => 
        array (
          'type' => 'textarea',
          'label' => 'Subtitle Hero',
        ),
        'info1_title' => 
        array (
          'type' => 'text',
          'label' => 'Info 1 — Judul (mis: Alamat)',
        ),
        'info1_desc' => 
        array (
          'type' => 'text',
          'label' => 'Info 1 — Detail',
        ),
        'info2_title' => 
        array (
          'type' => 'text',
          'label' => 'Info 2 — Judul (mis: Telepon)',
        ),
        'info2_desc' => 
        array (
          'type' => 'text',
          'label' => 'Info 2 — Detail',
        ),
        'info3_title' => 
        array (
          'type' => 'text',
          'label' => 'Info 3 — Judul (mis: Email)',
        ),
        'info3_desc' => 
        array (
          'type' => 'text',
          'label' => 'Info 3 — Detail',
        ),
        'info4_title' => 
        array (
          'type' => 'text',
          'label' => 'Info 4 — Judul (mis: Jam Buka)',
        ),
        'info4_desc' => 
        array (
          'type' => 'text',
          'label' => 'Info 4 — Detail',
        ),
        'form_title' => 
        array (
          'type' => 'text',
          'label' => 'Judul Form',
        ),
        'form_subtitle' => 
        array (
          'type' => 'text',
          'label' => 'Subtitle Form',
        ),
        'btn_text' => 
        array (
          'type' => 'text',
          'label' => 'Teks Tombol Kirim',
        ),
        'map_title' => 
        array (
          'type' => 'text',
          'label' => 'Judul Map',
        ),
        'map_embed' => 
        array (
          'type' => 'textarea',
          'label' => 'Embed Google Maps (kode iframe)',
        ),
        'social_wa' => 
        array (
          'type' => 'text',
          'label' => 'WhatsApp URL',
        ),
        'social_fb' => 
        array (
          'type' => 'text',
          'label' => 'Facebook URL',
        ),
        'social_ig' => 
        array (
          'type' => 'text',
          'label' => 'Instagram URL',
        ),
        'social_tw' => 
        array (
          'type' => 'text',
          'label' => 'X / Twitter URL',
        ),
        'social_yt' => 
        array (
          'type' => 'text',
          'label' => 'YouTube URL',
        ),
      ),
      'default' => 
      array (
        'badge' => 'Hubungi Kami',
        'title' => 'Get In Touch',
        'subtitle' => 'Kami siap membantu Anda. Kirimkan pesan dan tim kami akan merespons dalam 1×24 jam kerja.',
        'info1_title' => 'Alamat',
        'info1_desc' => 'Jl. Contoh No. 123, Jakarta',
        'info2_title' => 'Telepon',
        'info2_desc' => '+62 812-3456-7890',
        'info3_title' => 'Email',
        'info3_desc' => 'halo@example.com',
        'info4_title' => 'Jam Operasional',
        'info4_desc' => 'Senin–Jumat, 08.00–17.00',
        'form_title' => 'Kirim Pesan',
        'form_subtitle' => 'Isi formulir di bawah dan kami akan segera menghubungi Anda.',
        'btn_text' => 'Kirim Pesan',
        'map_title' => 'Lokasi Kami',
        'map_embed' => '',
        'social_wa' => '',
        'social_fb' => '',
        'social_ig' => '',
        'social_tw' => '',
        'social_yt' => '',
      ),
    ),
    'about' => 
    array (
      'name' => 'About Page',
      'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
      'component' => 'components.blocks.about',
      'fields' => 
      array (
        'badge' => 
        array (
          'type' => 'text',
          'label' => 'Badge Hero',
        ),
        'title' => 
        array (
          'type' => 'text',
          'label' => 'Judul Hero',
        ),
        'subtitle' => 
        array (
          'type' => 'textarea',
          'label' => 'Subtitle Hero',
        ),
        'hero_bg' => 
        array (
          'type' => 'image',
          'label' => 'Gambar Background',
        ),
        'story_title' => 
        array (
          'type' => 'text',
          'label' => 'Judul Cerita',
        ),
        'story_content' => 
        array (
          'type' => 'textarea',
          'label' => 'Konten Cerita',
        ),
        'story_image' => 
        array (
          'type' => 'image',
          'label' => 'Gambar Cerita',
        ),
        'vision_title' => 
        array (
          'type' => 'text',
          'label' => 'Judul Visi',
        ),
        'vision_text' => 
        array (
          'type' => 'textarea',
          'label' => 'Teks Visi',
        ),
        'mission_title' => 
        array (
          'type' => 'text',
          'label' => 'Judul Misi',
        ),
        'mission_text' => 
        array (
          'type' => 'textarea',
          'label' => 'Teks Misi',
        ),
        'stats' => 
        array (
          'type' => 'repeater',
          'label' => 'Statistik',
          'subfields' => 
          array (
            0 => 
            array (
              'key' => 'number',
              'type' => 'text',
              'label' => 'Angka (Mis: 10+)',
            ),
            1 => 
            array (
              'key' => 'label',
              'type' => 'text',
              'label' => 'Label (Mis: Tahun Pengalaman)',
            ),
          ),
        ),
      ),
      'default' => 
      array (
        'badge' => 'Tentang Kami',
        'title' => 'Mengenal Lebih Dekat',
        'subtitle' => 'Kami berdedikasi untuk memberikan solusi terbaik dan inovatif bagi kebutuhan digital Anda.',
        'hero_bg' => '',
        'story_title' => 'Cerita Perjalanan Kami',
        'story_content' => 'Berawal dari visi sederhana, kami terus berkembang dan berinovasi. Tim kami terdiri dari para ahli yang bersemangat untuk menciptakan produk yang berdampak positif.',
        'story_image' => '',
        'vision_title' => 'Visi Kami',
        'vision_text' => 'Menjadi pelopor inovasi digital yang memberikan nilai tambah bagi masyarakat dan bisnis di seluruh dunia.',
        'mission_title' => 'Misi Kami',
        'mission_text' => 'Mengembangkan produk berkualitas, membangun kolaborasi yang kuat, dan terus beradaptasi dengan teknologi terbaru.',
        'stats' => 
        array (
          0 => 
          array (
            'number' => '10+',
            'label' => 'Tahun Pengalaman',
          ),
          1 => 
          array (
            'number' => '500+',
            'label' => 'Klien Bahagia',
          ),
          2 => 
          array (
            'number' => '50+',
            'label' => 'Anggota Tim',
          ),
        ),
      ),
    ),
    'home_hero' => 
    array (
      'name' => 'Home Hero',
      'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>',
      'component' => 'components.blocks.home_hero',
      'fields' => 
      array (
        'hero_bg' => 
        array (
          'type' => 'image',
          'label' => 'Gambar Latar (Desktop)',
        ),
        'bg_position' => 
        array (
          'type' => 'select',
          'label' => 'Posisi Gambar',
          'options' => 
          array (
            'center' => 'Tengah (Center)',
            'top' => 'Atas (Top)',
            'bottom' => 'Bawah (Bottom)',
            'left' => 'Kiri (Left)',
            'right' => 'Kanan (Right)',
          ),
        ),
        'hero_bg_mobile' => 
        array (
          'type' => 'image',
          'label' => 'Gambar Latar (Mobile)',
        ),
        'bg_position_mobile' => 
        array (
          'type' => 'select',
          'label' => 'Posisi Gambar (Mobile)',
          'options' => 
          array (
            'center' => 'Tengah (Center)',
            'top' => 'Atas (Top)',
            'bottom' => 'Bawah (Bottom)',
            'left' => 'Kiri (Left)',
            'right' => 'Kanan (Right)',
          ),
        ),
        'badge' => 
        array (
          'type' => 'text',
          'label' => 'Badge Text',
        ),
        'title' => 
        array (
          'type' => 'textarea',
          'label' => 'Judul',
        ),
        'subtitle' => 
        array (
          'type' => 'textarea',
          'label' => 'Sub Judul',
        ),
        'cta_primary_text' => 
        array (
          'type' => 'text',
          'label' => 'Tombol Utama (Teks)',
        ),
        'cta_primary_link' => 
        array (
          'type' => 'text',
          'label' => 'Tombol Utama (Link)',
        ),
        'cta_secondary_text' => 
        array (
          'type' => 'text',
          'label' => 'Tombol Kedua (Teks)',
        ),
        'cta_secondary_link' => 
        array (
          'type' => 'text',
          'label' => 'Tombol Kedua (Link)',
        ),
        'stats' => 
        array (
          'type' => 'repeater',
          'label' => 'Statistik',
          'subfields' => 
          array (
            0 => 
            array (
              'key' => 'number',
              'type' => 'text',
              'label' => 'Angka',
            ),
            1 => 
            array (
              'key' => 'label',
              'type' => 'text',
              'label' => 'Label',
            ),
          ),
        ),
      ),
      'default' => 
      array (
        'hero_bg' => '',
        'bg_position' => 'center',
        'hero_bg_mobile' => '',
        'bg_position_mobile' => 'center',
        'badge' => 'Selamat Datang',
        'title' => 'Bangun Website
Impian Anda',
        'subtitle' => 'Platform CMS profesional yang dirancang untuk membantu Anda membangun dan mengelola website dengan mudah dan cepat.',
        'cta_primary_text' => 'Mulai Sekarang',
        'cta_primary_link' => '/products',
        'cta_secondary_text' => 'Pelajari Lebih Lanjut',
        'cta_secondary_link' => '/posts',
        'stats' => 
        array (
          0 => 
          array (
            'number' => '10+',
            'label' => 'Tahun Pengalaman',
          ),
          1 => 
          array (
            'number' => '500+',
            'label' => 'Klien Puas',
          ),
          2 => 
          array (
            'number' => '99%',
            'label' => 'Uptime',
          ),
        ),
      ),
    ),
    'home_categories' => 
    array (
      'name' => 'Home Kategori',
      'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>',
      'component' => 'components.blocks.home_categories',
      'fields' => 
      array (
        'title' => 
        array (
          'type' => 'text',
          'label' => 'Judul Kategori',
        ),
        'image_shape' => 
        array (
          'type' => 'select',
          'label' => 'Bentuk Gambar',
          'options' => 
          array (
            'square' => 'Kotak (Rounded Square)',
            'circle' => 'Lingkaran (Circle)',
          ),
        ),
        'category_ids' => 
        array (
          'type' => 'product_categories',
          'label' => 'Pilih Kategori Produk',
        ),
      ),
      'default' => 
      array (
        'title' => 'Jelajahi Kategori',
        'image_shape' => 'square',
        'category_ids' => '',
      ),
    ),
    'home_products' => 
    array (
      'name' => 'Home Produk',
      'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>',
      'component' => 'components.blocks.home_products',
      'fields' => 
      array (
        'title' => 
        array (
          'type' => 'text',
          'label' => 'Judul Produk',
        ),
        'subtitle' => 
        array (
          'type' => 'textarea',
          'label' => 'Sub Judul',
        ),
        'limit' => 
        array (
          'type' => 'text',
          'label' => 'Jumlah Produk',
        ),
      ),
      'default' => 
      array (
        'title' => 'Produk Unggulan',
        'subtitle' => 'Temukan berbagai produk dan layanan terbaik kami.',
        'limit' => '8',
      ),
    ),
    'home_value_props' => 
    array (
      'name' => 'Home Mengapa Kami',
      'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.514M11 4v.01M11 8v.01"/></svg>',
      'component' => 'components.blocks.home_value_props',
      'fields' => 
      array (
        'subtitle' => 
        array (
          'type' => 'text',
          'label' => 'Subtitle Kecil',
        ),
        'title' => 
        array (
          'type' => 'text',
          'label' => 'Judul Utama',
        ),
        'props' => 
        array (
          'type' => 'repeater',
          'label' => 'Nilai Jual',
          'subfields' => 
          array (
            0 => 
            array (
              'key' => 'title',
              'type' => 'text',
              'label' => 'Judul',
            ),
            1 => 
            array (
              'key' => 'desc',
              'type' => 'textarea',
              'label' => 'Deskripsi',
            ),
          ),
        ),
      ),
      'default' => 
      array (
        'subtitle' => 'Mengapa Memilih Kami',
        'title' => 'Kenapa Kami?',
        'props' => 
        array (
          0 => 
          array (
            'title' => 'Kualitas Terbaik',
            'desc' => 'Kami menggunakan teknologi dan standar terbaik untuk memberikan hasil yang optimal bagi Anda.',
          ),
          1 => 
          array (
            'title' => 'Harga Terjangkau',
            'desc' => 'Nikmati layanan berkualitas dengan harga yang kompetitif dan transparan tanpa biaya tersembunyi.',
          ),
          2 => 
          array (
            'title' => 'Dukungan 24/7',
            'desc' => 'Tim support profesional kami siap membantu Anda kapan pun Anda membutuhkannya.',
          ),
        ),
      ),
    ),
    'home_testimonials' => 
    array (
      'name' => 'Home Testimoni',
      'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>',
      'component' => 'components.blocks.home_testimonials',
      'fields' => 
      array (
        'title' => 
        array (
          'type' => 'text',
          'label' => 'Judul Testimoni',
        ),
        'testimonials' => 
        array (
          'type' => 'repeater',
          'label' => 'Daftar Testimoni',
          'subfields' => 
          array (
            0 => 
            array (
              'key' => 'name',
              'type' => 'text',
              'label' => 'Nama',
            ),
            1 => 
            array (
              'key' => 'role',
              'type' => 'text',
              'label' => 'Peran / Jabatan',
            ),
            2 => 
            array (
              'key' => 'text',
              'type' => 'textarea',
              'label' => 'Teks Testimoni',
            ),
          ),
        ),
      ),
      'default' => 
      array (
        'title' => 'Apa Kata Pelanggan Kami',
        'testimonials' => 
        array (
          0 => 
          array (
            'name' => 'Ahmad Fauzi',
            'role' => 'Pengusaha',
            'text' => 'Platform ini sangat membantu bisnis saya berkembang pesat!',
          ),
          1 => 
          array (
            'name' => 'Dewi Sartika',
            'role' => 'Content Creator',
            'text' => 'Sangat mudah digunakan, fiturnya lengkap dan supportnya cepat.',
          ),
          2 => 
          array (
            'name' => 'Budi Santoso',
            'role' => 'Owner Toko Online',
            'text' => 'CMS terbaik yang pernah saya gunakan. Sangat direkomendasikan!',
          ),
        ),
      ),
    ),
    'home_news' => 
    array (
      'name' => 'Home Berita',
      'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>',
      'component' => 'components.blocks.home_news',
      'fields' => 
      array (
        'title' => 
        array (
          'type' => 'text',
          'label' => 'Judul Section',
        ),
        'limit' => 
        array (
          'type' => 'text',
          'label' => 'Jumlah Artikel',
        ),
      ),
      'default' => 
      array (
        'title' => 'Artikel Terbaru',
        'limit' => '3',
      ),
    ),
    'home_cta' => 
    array (
      'name' => 'Home CTA (Newsletter)',
      'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
      'component' => 'components.blocks.home_cta',
      'fields' => 
      array (
        'title' => 
        array (
          'type' => 'text',
          'label' => 'Judul',
        ),
        'subtitle' => 
        array (
          'type' => 'textarea',
          'label' => 'Sub Judul',
        ),
        'button_text' => 
        array (
          'type' => 'text',
          'label' => 'Teks Tombol',
        ),
      ),
      'default' => 
      array (
        'title' => 'Tetap Terhubung Dengan Kami',
        'subtitle' => 'Berlangganan newsletter kami untuk mendapatkan informasi terbaru, tips, dan penawaran menarik.',
        'button_text' => 'Berlangganan',
      ),
    ),
  ),
);
