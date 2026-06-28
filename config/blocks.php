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
  ),
);
