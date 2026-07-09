<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Page;
use App\Models\Post;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class CustomizerController extends Controller
{
    /**
     * Display the Homepage Customizer form.
     */
    public function indexCustomizer()
    {
        $pages = Page::where('status', 'published')->orderBy('title', 'asc')->get();

        // Load existing settings with fallbacks
        $homepageType = Setting::get('homepage_type', 'default');
        $homepageStaticPageId = Setting::get('homepage_static_page_id', '');

        $sectionsOrder = json_decode(Setting::get('homepage_sections_order', '["hero","stats","tri_column","about","lecturers","downloads","news"]'), true);
        if (!$sectionsOrder) {
            $sectionsOrder = ["hero", "stats", "tri_column", "about", "lecturers", "downloads", "news"];
        }

        $sectionsVisibility = json_decode(Setting::get('homepage_sections_visibility', '{"hero":true,"stats":true,"tri_column":true,"about":true,"lecturers":true,"downloads":true,"news":true}'), true);
        if (!$sectionsVisibility) {
            $sectionsVisibility = ["hero" => true, "stats" => true, "tri_column" => true, "about" => true, "lecturers" => true, "downloads" => true, "news" => true];
        }

        // Hero Section
        $heroContent = json_decode(Setting::get('homepage_hero_content', '{}'), true);
        $heroDefaults = [
            'badge' => '🎓 AKREDITASI B (BAN-PT)',
            'title' => 'Membentuk Pemimpin Administrasi & Bisnis Modern',
            'subtitle' => 'Siap berkarier sebagai sekretaris eksekutif, manajer kantor digital, maupun wirausahawan mandiri yang menguasai teknologi informasi di era global.',
            'btn1_label' => 'Lihat Kurikulum',
            'btn1_url' => '/page/kurikulum',
            'btn2_label' => 'Profil Prodi',
            'btn2_url' => '/page/profil-jurusan',
            'show_inquiry' => true
        ];
        $hero = array_merge($heroDefaults, $heroContent);

        // Tri-Column Section
        $triColumnContent = json_decode(Setting::get('homepage_tricolumn_content', '{}'), true);
        $triColumnDefaults = [
            'col1_title' => 'Berita Terbaru',
            'col1_show_news' => true,
            'col2_title' => 'Agenda & Event',
            'col2_events' => [
                ['day' => '15', 'month' => 'Jul', 'title' => 'Workshop Business Plan & Tenant Inkubator', 'meta' => '09:00 - selesai | Aula LPKIA'],
                ['day' => '10', 'month' => 'Agt', 'title' => 'Campus Hiring & Rekrutmen Bersama BUMN', 'meta' => '08:30 - selesai | Lab Perkantoran'],
                ['day' => '01', 'month' => 'Sep', 'title' => 'PKKMB & Orientasi Mahasiswa Baru Administrasi Bisnis', 'meta' => '07:30 - selesai | Kampus LPKIA']
            ],
            'col3_title' => 'Tentang Program Studi',
            'col3_text' => 'Selamat datang di Program Studi Administrasi Bisnis LPKIA Bandung. Kami mempersiapkan tenaga ahli administrasi perkantoran unggul dan wirausahawan mandiri yang menguasai perangkat digital.',
            'col3_link_url' => '/page/profil-jurusan',
            'col3_link_label' => 'Pelajari Selengkapnya'
        ];
        $triColumn = array_merge($triColumnDefaults, $triColumnContent);

        // About & Visi Misi Section
        $aboutContent = json_decode(Setting::get('homepage_about_content', '{}'), true);
        $aboutDefaults = [
            'about_title' => 'Mempersiapkan Karier di Era Bisnis Digital',
            'about_subtitle' => 'TENTANG PROGRAM STUDI',
            'about_text1' => 'Program Studi Administrasi Bisnis LPKIA dirancang secara khusus untuk membekali mahasiswa dengan keterampilan administrasi perkantoran, kesekretariatan modern, kearsipan elektronik, dan pemasaran digital berbasis teknologi informasi.',
            'about_text2' => 'Kami menekankan pembelajaran praktikum (learning by doing) agar setiap lulusan tidak hanya menguasai teori bisnis, melainkan terampil mengeksekusi operasional kantor modern dan siap mendirikan usaha mandiri yang kreatif.',
            'about_link_url' => '/page/profil-jurusan',
            'about_link_label' => 'Pelajari Selengkapnya',
            'visi_title' => 'Visi Program Studi',
            'visi_text' => '"Menjadi Program Studi Administrasi Bisnis yang unggul dan terkemuka di tingkat nasional dalam menghasilkan tenaga profesional administrasi perkantoran digital yang berintegritas, mandiri, dan berjiwa wirausaha kreatif berbasis teknologi informasi pada tahun 2030."',
            'misi_title' => 'Misi Utama',
            'misi_items' => [
                'Menyelenggarakan pendidikan vokasi administrasi bisnis berkualitas tinggi.',
                'Melaksanakan penelitian terapan dan pengabdian dalam pengembangan kewirausahaan.',
                'Membangun kemitraan strategis dengan dunia usaha, industri, dan asosiasi profesi.'
            ],
            'show_org' => true
        ];
        $about = array_merge($aboutDefaults, $aboutContent);

        // Downloads Section
        $downloadsContent = json_decode(Setting::get('homepage_downloads_content', '{}'), true);
        $downloadsDefaults = [
            'download_title' => 'Area Unduhan Dokumen',
            'download_subtitle' => 'Unduh berkas kurikulum resmi, panduan tugas akhir, serta silabus mata kuliah Program Studi Administrasi Bisnis LPKIA.',
            'downloads' => [
                ['name' => 'Kurikulum AB LPKIA 2026.pdf', 'meta' => 'Ukuran: 1.4 MB | PDF Document', 'url' => '#'],
                ['name' => 'Panduan Magang Kerja AB.pdf', 'meta' => 'Ukuran: 920 KB | PDF Document', 'url' => '#']
            ],
            'announcement_badge' => 'Pengumuman Terkini',
            'announcement_title' => 'Pembagian Kelompok Simulasi Perkantoran',
            'announcement_text' => 'Kelompok simulasi perkantoran untuk Semester Ganjil TA 2026/2027 telah diumumkan di mading utama prodi dan sistem informasi mahasiswa. Harap melakukan registrasi kelompok paling lambat 20 Agustus 2026.',
            'announcement_btn_label' => 'Lihat Jadwal Kelas',
            'announcement_btn_url' => '#jadwal-kelas'
        ];
        $downloads = array_merge($downloadsDefaults, $downloadsContent);

        // Colors & Theme settings
        $theme = [
            'theme_primary_color' => Setting::get('theme_primary_color', '#f25c3f'),
            'theme_primary_deep_color' => Setting::get('theme_primary_deep_color', '#e14a2c'),
            'theme_brand_teal_deep_color' => Setting::get('theme_brand_teal_deep_color', '#0c2340'),
            'theme_brand_teal_color' => Setting::get('theme_brand_teal_color', '#0f2d52'),
            'theme_brand_teal_mid_color' => Setting::get('theme_brand_teal_mid_color', '#164275'),
            'theme_font_family' => Setting::get('theme_font_family', 'Plus Jakarta Sans'),
            'theme_custom_css' => Setting::get('theme_custom_css', ''),
        ];

        return view('admin.customizer.index', compact(
            'pages',
            'homepageType',
            'homepageStaticPageId',
            'sectionsOrder',
            'sectionsVisibility',
            'hero',
            'triColumn',
            'about',
            'downloads',
            'theme'
        ));
    }

    /**
     * Process and Save the Homepage Customizer data.
     */
    public function updateCustomizer(Request $request)
    {
        $request->validate([
            'homepage_type' => 'required|in:default,static_page',
            'homepage_static_page_id' => 'nullable|integer',
            'sections_order' => 'required|string',
            'sections_visibility' => 'required|string',
            
            // Hero
            'hero.badge' => 'required|string|max:255',
            'hero.title' => 'required|string|max:255',
            'hero.subtitle' => 'required|string',
            'hero.btn1_label' => 'nullable|string|max:100',
            'hero.btn1_url' => 'nullable|string|max:255',
            'hero.btn2_label' => 'nullable|string|max:100',
            'hero.btn2_url' => 'nullable|string|max:255',
            
            // Tri-Column
            'tri_column.col1_title' => 'required|string|max:255',
            'tri_column.col2_title' => 'required|string|max:255',
            'tri_column.col3_title' => 'required|string|max:255',
            'tri_column.col3_text' => 'required|string',
            'tri_column.col3_link_url' => 'nullable|string|max:255',
            'tri_column.col3_link_label' => 'nullable|string|max:100',

            // About
            'about.about_title' => 'required|string|max:255',
            'about.about_subtitle' => 'required|string|max:255',
            'about.about_text1' => 'required|string',
            'about.about_text2' => 'required|string',
            'about.about_link_url' => 'nullable|string|max:255',
            'about.about_link_label' => 'nullable|string|max:100',
            'about.visi_title' => 'required|string|max:255',
            'about.visi_text' => 'required|string',
            'about.misi_title' => 'required|string|max:255',

            // Downloads
            'downloads.download_title' => 'required|string|max:255',
            'downloads.download_subtitle' => 'required|string',
            'downloads.announcement_badge' => 'required|string|max:255',
            'downloads.announcement_title' => 'required|string|max:255',
            'downloads.announcement_text' => 'required|string',
            'downloads.announcement_btn_label' => 'nullable|string|max:100',
            'downloads.announcement_btn_url' => 'nullable|string|max:255',

            // Colors & Fonts Theme settings
            'theme_primary_color' => 'nullable|string|max:7',
            'theme_primary_deep_color' => 'nullable|string|max:7',
            'theme_brand_teal_deep_color' => 'nullable|string|max:7',
            'theme_brand_teal_color' => 'nullable|string|max:7',
            'theme_brand_teal_mid_color' => 'nullable|string|max:7',
            'theme_font_family' => 'nullable|string|max:100',
            'theme_custom_css' => 'nullable|string',
        ]);

        Setting::set('homepage_type', $request->homepage_type);
        Setting::set('homepage_static_page_id', $request->homepage_static_page_id ?? '');

        // Save order & visibilities
        $orderArray = json_decode($request->sections_order, true);
        if (is_array($orderArray)) {
            Setting::set('homepage_sections_order', json_encode($orderArray));
        }

        $visibilityArray = json_decode($request->sections_visibility, true);
        if (is_array($visibilityArray)) {
            Setting::set('homepage_sections_visibility', json_encode($visibilityArray));
        }

        // Save Hero
        $heroData = $request->hero;
        $heroData['show_inquiry'] = $request->has('hero.show_inquiry');
        Setting::set('homepage_hero_content', json_encode($heroData));

        // Save Tri-Column & Events
        $triColumnData = $request->tri_column;
        $triColumnData['col1_show_news'] = $request->has('tri_column.col1_show_news');
        
        // Parse dynamic events
        $events = [];
        if ($request->has('events') && is_array($request->events)) {
            foreach ($request->events as $event) {
                if (!empty($event['title'])) {
                    $events[] = [
                        'day' => $event['day'] ?? '',
                        'month' => $event['month'] ?? '',
                        'title' => $event['title'] ?? '',
                        'meta' => $event['meta'] ?? '',
                    ];
                }
            }
        }
        $triColumnData['col2_events'] = $events;
        Setting::set('homepage_tricolumn_content', json_encode($triColumnData));

        // Save About & Misi Items
        $aboutData = $request->about;
        $aboutData['show_org'] = $request->has('about.show_org');
        
        $misiItems = [];
        if ($request->has('misi') && is_array($request->misi)) {
            foreach ($request->misi as $item) {
                if (!empty($item)) {
                    $misiItems[] = $item;
                }
            }
        }
        $aboutData['misi_items'] = $misiItems;
        Setting::set('homepage_about_content', json_encode($aboutData));

        // Save Downloads list
        $downloadsData = $request->downloads;
        $downloads = [];
        if ($request->has('dls') && is_array($request->dls)) {
            foreach ($request->dls as $dl) {
                if (!empty($dl['name'])) {
                    $downloads[] = [
                        'name' => $dl['name'],
                        'meta' => $dl['meta'] ?? '',
                        'url' => $dl['url'] ?? '#',
                    ];
                }
            }
        }
        $downloadsData['downloads'] = $downloads;
        Setting::set('homepage_downloads_content', json_encode($downloadsData));

        // Save Colors & Fonts Theme
        Setting::set('theme_primary_color', $request->theme_primary_color ?? '#f25c3f');
        Setting::set('theme_primary_deep_color', $request->theme_primary_deep_color ?? '#e14a2c');
        Setting::set('theme_brand_teal_deep_color', $request->theme_brand_teal_deep_color ?? '#0c2340');
        Setting::set('theme_brand_teal_color', $request->theme_brand_teal_color ?? '#0f2d52');
        Setting::set('theme_brand_teal_mid_color', $request->theme_brand_teal_mid_color ?? '#164275');
        Setting::set('theme_font_family', $request->theme_font_family ?? 'Plus Jakarta Sans');
        Setting::set('theme_custom_css', $request->theme_custom_css ?? '');

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Update Homepage Customizer & Theme',
            'details' => 'Updated customizable sections, colors, and layout for public homepage.'
        ]);

        return redirect()->route('admin.customizer.index')->with('success', 'Homepage Customizer & Tema warna berhasil diperbarui.');
    }

    /**
     * Display the Navbar Menu settings.
     */
    public function indexMenu()
    {
        $pages = Page::where('status', 'published')->orderBy('title', 'asc')->get();
        $posts = Post::where('status', 'published')->orderBy('title', 'asc')->take(15)->get();

        // Load existing menu structure
        $menuRaw = Setting::get('site_menu', '[]');
        $menuItems = json_decode($menuRaw, true);
        if (!is_array($menuItems)) {
            // Default Fallback Menu Items
            $menuItems = [
                ['label' => 'Beranda', 'url' => '/'],
                ['label' => 'Profil', 'url' => '#', 'children' => [
                    ['label' => 'Profil Prodi', 'url' => '/page/profil-jurusan'],
                    ['label' => 'Visi & Misi', 'url' => '/page/visi-misi'],
                    ['label' => 'Fasilitas Lab', 'url' => '/page/fasilitas']
                ]],
                ['label' => 'Kurikulum', 'url' => '/page/kurikulum'],
                ['label' => 'Dosen', 'url' => '/#dosen'],
                ['label' => 'Berita', 'url' => '/berita'],
                ['label' => 'Kontak', 'url' => '/page/kontak']
            ];
        }

        return view('admin.menus.index', compact('pages', 'posts', 'menuItems'));
    }

    /**
     * Process and Save the Menu configuration.
     */
    public function updateMenu(Request $request)
    {
        $request->validate([
            'menu_structure' => 'required|json'
        ]);

        Setting::set('site_menu', $request->menu_structure);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Update Navigation Menu',
            'details' => 'Reconfigured navigation menu items for header navbar.'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Menu navigasi berhasil disimpan.'
        ]);
    }

    /**
     * Display the Schedule Manager.
     */
    public function indexSchedule()
    {
        $scheduleContent = json_decode(Setting::get('homepage_schedule_content', '{}'), true);
        $scheduleDefaults = [
            'schedule_title' => 'Jadwal Kelas Unggulan',
            'schedule_subtitle' => 'Jadwal perkuliahan praktikum mingguan mahasiswa Administrasi Bisnis di berbagai laboratorium simulasi dan komputer.',
            'schedule_rows' => [
                ['day' => 'Senin', 'time' => '08:00 - 10:30', 'course' => 'Aplikasi Perkantoran Digital', 'lecturer' => 'Dra. Endang Setyowati, M.M.', 'room' => 'Lab Komputer Bisnis 3', 'badge_type' => 'success'],
                ['day' => 'Selasa', 'time' => '10:40 - 13:10', 'course' => 'Korespondensi Bisnis', 'lecturer' => 'Rina Herlina, S.Sos., M.AB.', 'room' => 'Ruang Teori 402', 'badge_type' => 'secondary'],
                ['day' => 'Rabu', 'time' => '13:30 - 16:00', 'course' => 'Manajemen Kearsipan Elektronik', 'lecturer' => 'Rina Herlina, S.Sos., M.AB.', 'room' => 'Lab Kearsipan Digital', 'badge_type' => 'success'],
                ['day' => 'Kamis', 'time' => '08:00 - 10:30', 'course' => 'Pemasaran Digital & E-Commerce', 'lecturer' => 'Yusuf Maulana, M.B.A.', 'room' => 'Lab Komputer Bisnis 1', 'badge_type' => 'success'],
                ['day' => 'Jumat', 'time' => '10:00 - 12:30', 'course' => 'Kewirausahaan Mandiri', 'lecturer' => 'Budi Setiawan, S.E., M.M.', 'room' => 'Inkubator Bisnis', 'badge_type' => 'secondary']
            ]
        ];
        $schedule = array_merge($scheduleDefaults, $scheduleContent);

        return view('admin.schedule.index', compact('schedule'));
    }

    /**
     * Update the Schedule settings.
     */
    public function updateSchedule(Request $request)
    {
        $request->validate([
            'schedule_title' => 'required|string|max:255',
            'schedule_subtitle' => 'required|string',
        ]);

        $scheduleData = [
            'schedule_title' => $request->schedule_title,
            'schedule_subtitle' => $request->schedule_subtitle,
        ];

        $rows = [];
        if ($request->has('rows') && is_array($request->rows)) {
            foreach ($request->rows as $row) {
                if (!empty($row['course'])) {
                    $rows[] = [
                        'day' => $row['day'] ?? '',
                        'time' => $row['time'] ?? '',
                        'course' => $row['course'] ?? '',
                        'lecturer' => $row['lecturer'] ?? '',
                        'room' => $row['room'] ?? '',
                        'badge_type' => $row['badge_type'] ?? 'success',
                    ];
                }
            }
        }
        $scheduleData['schedule_rows'] = $rows;

        Setting::set('homepage_schedule_content', json_encode($scheduleData));

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Update Class Schedule',
            'details' => 'Reconfigured class schedule list in schedule manager.'
        ]);

        return redirect()->route('admin.schedule.index')->with('success', 'Jadwal Kelas berhasil diperbarui.');
    }
}
