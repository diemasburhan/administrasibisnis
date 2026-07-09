<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Lecturer;
use App\Models\OrgMember;

class HomeController extends Controller
{
    public function index()
    {
        $homepageType = Setting::get('homepage_type', 'default');
        if ($homepageType === 'static_page') {
            $staticPageId = Setting::get('homepage_static_page_id');
            if ($staticPageId) {
                $page = Page::where('id', $staticPageId)->where('status', 'published')->first();
                if ($page) {
                    return view('public.page-detail', compact('page'));
                }
            }
        }

        $posts = Post::where('status', 'published')
            ->where(function($query) {
                $query->whereNull('published_at')
                      ->orWhere('published_at', '<=', now());
            })
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $pages = Page::where('status', 'published')->get();

        // Load stats from Settings
        $stats = [
            'total_students' => Setting::get('stats_total_students', '2450'),
            'active_students' => Setting::get('stats_active_students', '2180'),
            'graduates' => Setting::get('stats_graduates', '12800'),
            'employment_rate' => Setting::get('stats_employment_rate', '94%'),
            'majors' => json_decode(Setting::get('stats_majors_data', '[]'), true),
            'genders' => json_decode(Setting::get('stats_gender_data', '[]'), true),
            'enrollment' => json_decode(Setting::get('stats_yearly_enrollment', '[]'), true),
        ];

        $lecturers = Lecturer::orderBy('name', 'asc')->get();
        $orgMembers = OrgMember::orderBy('sort_order', 'asc')->get();

        // Customizer configurations
        $sectionsOrder = json_decode(Setting::get('homepage_sections_order', '["hero","stats","tri_column","about","lecturers","downloads","news"]'), true);
        if (!is_array($sectionsOrder)) {
            $sectionsOrder = ["hero", "stats", "tri_column", "about", "lecturers", "downloads", "news"];
        }

        $sectionsVisibility = json_decode(Setting::get('homepage_sections_visibility', '{"hero":true,"stats":true,"tri_column":true,"about":true,"lecturers":true,"downloads":true,"news":true}'), true);
        if (!is_array($sectionsVisibility)) {
            $sectionsVisibility = ["hero" => true, "stats" => true, "tri_column" => true, "about" => true, "lecturers" => true, "downloads" => true, "news" => true];
        }

        // Section contents
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

        return view('public.home', compact(
            'posts',
            'pages',
            'stats',
            'lecturers',
            'orgMembers',
            'sectionsOrder',
            'sectionsVisibility',
            'hero',
            'triColumn',
            'about',
            'downloads',
            'schedule'
        ));
    }

    public function post($slug)
    {
        $post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->with(['category', 'author', 'comments' => function($q) {
                $q->where('status', 'approved')->orderBy('created_at', 'desc');
            }])
            ->firstOrFail();

        $categories = Category::withCount(['posts' => function($q) {
            $q->where('status', 'published');
        }])->get();

        $recentPosts = Post::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        return view('public.post-detail', compact('post', 'categories', 'recentPosts'));
    }

    public function posts()
    {
        $posts = Post::where('status', 'published')
            ->where(function($query) {
                $query->whereNull('published_at')
                      ->orWhere('published_at', '<=', now());
            })
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        return view('public.posts', compact('posts'));
    }

    public function page($slug)
    {
        $page = Page::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('public.page-detail', compact('page'));
    }

    public function comment(Request $request, $postId)
    {
        $request->validate([
            'author_name' => 'required|string|max:255',
            'author_email' => 'required|email|max:255',
            'content' => 'required|string|min:5',
        ]);

        Comment::create([
            'post_id' => $postId,
            'author_name' => $request->author_name,
            'author_email' => $request->author_email,
            'content' => $request->content,
            'status' => 'pending', // Awaiting approval
        ]);

        return back()->with('success', 'Komentar Anda telah dikirim dan menunggu persetujuan admin.');
    }
}
