<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Models\Category;
use App\Models\Post;
use App\Models\Page;
use App\Models\Comment;
use App\Models\ActivityLog;
use App\Models\Setting;
use App\Models\Lecturer;
use App\Models\OrgMember;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Seed Users
        $admin = User::create([
            'name' => 'Administrator Administrasi Bisnis',
            'email' => 'admin@lpkia.ac.id',
            'password' => 'adminpassword', // Auto-hashed by User model mutator
            'role' => 'admin',
        ]);

        $staff = User::create([
            'name' => 'Staf Administrasi Bisnis',
            'email' => 'staff@lpkia.ac.id',
            'password' => 'staffpassword',
            'role' => 'staff',
        ]);

        $adbisStaff = User::create([
            'name' => 'Adbis Staff',
            'email' => 'adbis.staff@lpkia.ac.id',
            'password' => 'adbisstaffpassword',
            'role' => 'adbis staff',
        ]);

        // 2. Seed Categories
        $categories = [
            ['name' => 'Akademik', 'slug' => 'akademik'],
            ['name' => 'Kemahasiswaan', 'slug' => 'kemahasiswaan'],
            ['name' => 'Pengumuman', 'slug' => 'pengumuman'],
            ['name' => 'Karir & Magang', 'slug' => 'karir-magang'],
            ['name' => 'Workshop & Event', 'slug' => 'workshop-event'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Get Category Instances
        $catAcademic = Category::where('slug', 'akademik')->first();
        $catAnnouncement = Category::where('slug', 'pengumuman')->first();
        $catEvent = Category::where('slug', 'workshop-event')->first();
        $catCareer = Category::where('slug', 'karir-magang')->first();

        // 3. Seed Settings (Business Admin themed stats)
        $settings = [
            'site_name' => 'Administrasi Bisnis LPKIA',
            'site_tagline' => 'Membentuk Profesional Administrasi Perkantoran Digital & Wirausaha Muda Kreatif',
            'site_address' => 'Jl. Soekarno-Hatta No. 456, Bandung, Jawa Barat',
            'site_phone' => '(022) 7564200',
            'site_email' => 'admbisnis@lpkia.ac.id',
            
            // Statistics Summary
            'stats_total_students' => '650',
            'stats_active_students' => '580',
            'stats_graduates' => '3200',
            'stats_employment_rate' => '96%',
            
            // Charts data
            'stats_majors_data' => json_encode([
                ['name' => 'Administrasi Perkantoran', 'students' => 280, 'color' => '#00ed64'],
                ['name' => 'Kewirausahaan Digital', 'students' => 220, 'color' => '#7b3ff2'],
                ['name' => 'Bisnis Ritel & Pemasaran', 'students' => 150, 'color' => '#fa6e39']
            ]),
            'stats_gender_data' => json_encode([
                ['label' => 'Perempuan', 'value' => 410, 'color' => '#f06bb8'],
                ['label' => 'Laki-laki', 'value' => 240, 'color' => '#3d4f9f']
            ]),
            'stats_yearly_enrollment' => json_encode([
                ['year' => '2022', 'students' => 120],
                ['year' => '2023', 'students' => 135],
                ['year' => '2024', 'students' => 150],
                ['year' => '2025', 'students' => 165],
                ['year' => '2026', 'students' => 180]
            ])
        ];

        foreach ($settings as $key => $val) {
            Setting::set($key, $val);
        }

        // 4. Seed Posts
        $posts = [
            [
                'category_id' => $catAnnouncement->id,
                'user_id' => $admin->id,
                'title' => 'Penerimaan Mahasiswa Baru Program Studi Administrasi Bisnis TA 2026/2027',
                'slug' => 'penerimaan-mahasiswa-baru-administrasi-bisnis-2026-2027',
                'content' => '<p>Pendaftaran Mahasiswa Baru (PMB) untuk program studi Administrasi Bisnis LPKIA kini telah dibuka! Dapatkan program beasiswa unggulan berupa potongan biaya kuliah hingga 100% bagi calon mahasiswa dengan prestasi akademik dan non-akademik.</p><p>Proses pendaftaran dapat diakses secara online melalui portal PMB resmi kami. Segera daftarkan diri Anda dan jadilah bagian dari program studi yang melahirkan wirausahawan digital terkemuka!</p>',
                'featured_image' => 'pmb_banner.jpg',
                'status' => 'published',
                'published_at' => now(),
            ],
            [
                'category_id' => $catEvent->id,
                'user_id' => $admin->id,
                'title' => 'Workshop Entrepreneurship: Menyusun Business Plan Kreatif Era Digital',
                'slug' => 'workshop-entrepreneurship-menyusun-business-plan-kreatif-era-digital',
                'content' => '<p>Program Studi Administrasi Bisnis LPKIA menyelenggarakan workshop interaktif mengenai cara menyusun Business Plan kreatif yang bernilai jual tinggi. Acara ini ditujukan untuk seluruh mahasiswa aktif Administrasi Bisnis.</p><p>Peserta akan dibimbing langsung oleh para praktisi bisnis dan startup inkubator untuk menuangkan ide-ide inovatif ke dalam dokumen bisnis yang matang dan siap dipresentasikan di hadapan investor.</p>',
                'featured_image' => 'workshop_plan.jpg',
                'status' => 'published',
                'published_at' => now()->subDays(2),
            ],
            [
                'category_id' => $catCareer->id,
                'user_id' => $staff->id,
                'title' => 'Pelepasan Program Magang Kerja Mahasiswa di BUMN dan Perusahaan Swasta Nasional',
                'slug' => 'pelepasan-program-magang-kerja-mahasiswa-bumn-swasta-nasional',
                'content' => '<p>Sebanyak 45 mahasiswa tingkat akhir Program Studi Administrasi Bisnis LPKIA secara resmi dilepas untuk mengikuti program magang kerja (internship) selama 6 bulan di berbagai BUMN dan perusahaan swasta mitra LPKIA.</p><p>Program ini dirancang untuk memberikan pengalaman kerja nyata, mengasah kompetensi administrasi perkantoran modern, serta memperkuat jejaring profesional sebelum lulus kuliah.</p>',
                'featured_image' => 'magang_pelepasan.jpg',
                'status' => 'published',
                'published_at' => now()->subDays(5),
            ],
            [
                'category_id' => $catAcademic->id,
                'user_id' => $admin->id,
                'title' => 'Pentingnya Sertifikasi Keahlian Microsoft Office Specialist (MOS) bagi Mahasiswa Administrasi',
                'slug' => 'pentingnya-sertifikasi-keahlian-microsoft-office-specialist-mos-mahasiswa',
                'content' => '<p>Sertifikasi MOS (Microsoft Office Specialist) menjadi bukti keahlian yang sangat diakui di dunia kerja global untuk keahlian administrasi perkantoran digital.</p>',
                'featured_image' => null,
                'status' => 'draft',
                'published_at' => null,
            ],
            [
                'category_id' => $catEvent->id,
                'user_id' => $staff->id,
                'title' => 'Seminar Korespondensi Bisnis Modern dan Kearsipan Elektronik',
                'slug' => 'seminar-korespondensi-bisnis-modern-kearsipan-elektronik',
                'content' => '<p>Seminar ini dijadwalkan bagi mahasiswa tingkat akhir demi membekali kesiapan kerja di bidang kesekretariatan dan manajemen arsip berbasis cloud.</p>',
                'featured_image' => null,
                'status' => 'scheduled',
                'published_at' => now()->addDays(3),
            ]
        ];

        foreach ($posts as $postData) {
            Post::create($postData);
        }

        // 5. Seed Pages
        $pages = [
            [
                'title' => 'Profil Program Studi',
                'slug' => 'profil-jurusan',
                'content' => '<h2>Profil Program Studi Administrasi Bisnis LPKIA</h2><p>Program Studi Administrasi Bisnis LPKIA berfokus pada pengembangan kompetensi mahasiswa di bidang administrasi perkantoran modern (kesekretariatan) berbasis digital dan pembentukan mental wirausaha mandiri. Kurikulum kami diselaraskan dengan kebutuhan industri masa kini, diperkaya dengan praktikum intensif yang mencakup penggunaan aplikasi perkantoran terintegrasi, korespondensi bisnis Indonesia-Inggris, digital marketing, manajemen kearsipan elektronik, hingga simulasi bisnis perkantoran.</p><h3>Mengapa Memilih Kami?</h3><ul><li>Kurikulum Link & Match dengan dunia industri.</li><li>Fasilitas laboratorium simulasi perkantoran modern terlengkap.</li><li>Program magang wajib di perusahaan terkemuka.</li><li>Dosen pengajar bersertifikasi profesi dan praktisi bisnis aktif.</li><li>Lulusan siap kerja dengan tingkat keterserapan tinggi di pasar kerja.</li></ul>',
                'featured_image' => 'profil_ab.jpg',
                'status' => 'published'
            ],
            [
                'title' => 'Visi & Misi',
                'slug' => 'visi-misi',
                'content' => '<h2>Visi</h2><p>Menjadi Program Studi Administrasi Bisnis yang unggul dan terkemuka di tingkat nasional dalam menghasilkan tenaga profesional administrasi perkantoran digital yang berintegritas, mandiri, dan berjiwa wirausaha kreatif berbasis teknologi informasi pada tahun 2030.</p><h2>Misi</h2><ol><li>Menyelenggarakan pendidikan vokasi berkualitas tinggi di bidang administrasi bisnis yang berorientasi pada kebutuhan industri modern.</li><li>Melaksanakan penelitian terapan dan pengabdian masyarakat guna mendukung inovasi pengelolaan bisnis dan pengembangan kewirausahaan lokal.</li><li>Membangun kemitraan strategis yang berkelanjutan dengan dunia usaha, dunia industri, dan asosiasi profesi di tingkat nasional dan regional.</li><li>Membekali mahasiswa dengan sertifikasi profesi di bidang administrasi perkantoran digital dan kompetensi kewirausahaan kreatif.</li></ol>',
                'featured_image' => 'visi_misi.jpg',
                'status' => 'published'
            ],
            [
                'title' => 'Kurikulum',
                'slug' => 'kurikulum',
                'content' => '<h2>Kurikulum & Mata Kuliah Unggulan</h2><p>Berikut adalah beberapa mata kuliah unggulan yang ditawarkan pada Program Studi Administrasi Bisnis LPKIA untuk membentuk kesiapan kerja dan kewirausahaan mahasiswa:</p><ul><li><strong>Aplikasi Perkantoran Digital:</strong> Penguasaan tingkat lanjut Microsoft Office (Word, Excel, PowerPoint) dan Google Workspace secara kolaboratif.</li><li><strong>Korespondensi Bisnis:</strong> Penyusunan surat menyurat bisnis yang efektif dan profesional dalam Bahasa Indonesia maupun Bahasa Inggris.</li><li><strong>Kearsipan Elektronik (E-Filing):</strong> Metode pengelolaan arsip secara digital berbasis cloud computing dan keamanan dokumen.</li><li><strong>Pemasaran Digital & E-Commerce:</strong> Strategi pemasaran online, pengelolaan media sosial bisnis, SEO/SEM, dan manajemen toko online.</li><li><strong>Kewirausahaan Mandiri:</strong> Praktikum perancangan bisnis (business plan), inkubasi bisnis, dan simulasi pendirian startup.</li><li><strong>Simulasi Administrasi Perkantoran:</strong> Praktik kerja dalam laboratorium simulator perkantoran yang merepresentasikan alur bisnis sesungguhnya.</li></ul>',
                'featured_image' => 'kurikulum_ab.jpg',
                'status' => 'published'
            ],
            [
                'title' => 'Fasilitas Laboratorium',
                'slug' => 'fasilitas',
                'content' => '<h2>Fasilitas Laboratorium Administrasi Bisnis</h2><p>Kami menyediakan sarana dan prasarana perkuliahan yang modern untuk menjamin kenyamanan dan keseriusan praktikum mahasiswa:</p><ul><li><strong>Laboratorium Simulasi Perkantoran:</strong> Laboratorium yang dirancang menyerupai kantor fisik sesungguhnya, lengkap dengan resepsionis, ruang rapat, meja administrasi, dan perlengkapan perkantoran modern.</li><li><strong>Laboratorium Komputer Bisnis:</strong> Komputer dengan spesifikasi tinggi yang dilengkapi software administrasi perkantoran, digital design, dan software akuntansi bisnis.</li><li><strong>Inkubator Bisnis & Student Business Center:</strong> Wadah bagi mahasiswa yang ingin mengembangkan ide bisnis mereka dengan bimbingan mentor dan fasilitas co-working space.</li><li><strong>Perpustakaan Digital (E-Library):</strong> Akses ke ribuan jurnal, e-book bisnis, laporan magang, dan referensi akademik secara online.</li></ul>',
                'featured_image' => 'lab_simulasi.jpg',
                'status' => 'published'
            ]
        ];

        foreach ($pages as $pageData) {
            Page::create($pageData);
        }

        // 6. Seed Comments
        $post1 = Post::first();
        Comment::create([
            'post_id' => $post1->id,
            'author_name' => 'Budi Santoso',
            'author_email' => 'budi.santoso@gmail.com',
            'content' => 'Apakah pendaftaran beasiswa ini berlaku untuk lulusan SMK angkatan 2025 juga? Terima kasih.',
            'status' => 'approved',
        ]);
        Comment::create([
            'post_id' => $post1->id,
            'author_name' => 'Siti Aminah',
            'author_email' => 'siti.aminah@yahoo.com',
            'content' => 'Saya ingin bertanya syarat untuk magang kerja, apakah IPK minimal 3.0? Terima kasih.',
            'status' => 'pending',
        ]);

        // 7. Seed Activity Logs
        ActivityLog::create([
            'user_id' => $admin->id,
            'activity' => 'Login',
            'details' => 'Admin logged in from IP 127.0.0.1'
        ]);
        ActivityLog::create([
            'user_id' => $admin->id,
            'activity' => 'Create Post',
            'details' => 'Created new announcement post: Penerimaan Mahasiswa Baru Administrasi Bisnis'
        ]);

        // 8. Seed Lecturers
        $lecturers = [
            [
                'name' => 'Dr. H. Herwan, M.Si.',
                'nidn' => '0408106201',
                'position' => 'Kepala Program Studi & Dosen Utama',
                'email' => 'herwan@lpkia.ac.id',
                'expertise' => 'Manajemen Strategis & Kebijakan Bisnis',
                'photo' => null
            ],
            [
                'name' => 'Dra. Endang Setyowati, M.M.',
                'nidn' => '0415126402',
                'position' => 'Dosen Tetap / Koordinator Praktikum',
                'email' => 'endang.s@lpkia.ac.id',
                'expertise' => 'Administrasi Perkantoran & Kesekretariatan',
                'photo' => null
            ],
            [
                'name' => 'Budi Setiawan, S.E., M.M.',
                'nidn' => '0420087401',
                'position' => 'Dosen Tetap / Pembina Kemahasiswaan',
                'email' => 'budi.setiawan@lpkia.ac.id',
                'expertise' => 'Kewirausahaan & Manajemen Pemasaran',
                'photo' => null
            ],
            [
                'name' => 'Rina Herlina, S.Sos., M.AB.',
                'nidn' => '0402098203',
                'position' => 'Dosen Tetap',
                'email' => 'rina.herlina@lpkia.ac.id',
                'expertise' => 'Manajemen Kearsipan & Korespondensi Bisnis',
                'photo' => null
            ],
            [
                'name' => 'Yusuf Maulana, M.B.A.',
                'nidn' => '0411119001',
                'position' => 'Dosen Luar Biasa',
                'email' => 'yusuf.m@lpkia.ac.id',
                'expertise' => 'Digital Business & E-Commerce',
                'photo' => null
            ]
        ];

        foreach ($lecturers as $lecturer) {
            Lecturer::create($lecturer);
        }

        // 9. Seed OrgMembers
        $orgMembers = [
            [
                'name' => 'Dr. H. Herwan, M.Si.',
                'role' => 'Kepala Program Studi',
                'nip' => '19621008 198903 1 001',
                'photo' => null,
                'sort_order' => 1
            ],
            [
                'name' => 'Dra. Endang Setyowati, M.M.',
                'role' => 'Sekretaris Program Studi',
                'nip' => '19641215 199103 2 002',
                'photo' => null,
                'sort_order' => 2
            ],
            [
                'name' => 'Budi Setiawan, S.E., M.M.',
                'role' => 'Pembina Kemahasiswaan',
                'nip' => '19740820 200210 1 003',
                'photo' => null,
                'sort_order' => 3
            ]
        ];

        foreach ($orgMembers as $member) {
            OrgMember::create($member);
        }
    }
}