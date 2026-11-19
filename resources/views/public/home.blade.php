@extends('layouts.public')

@section('title', 'Administrasi Bisnis LPKIA - Unggul & Siap Kerja')

@section('styles')
<style>
    .hero-slider-container {
        position: relative;
        width: 100%;
        height: 400px;
        border-radius: var(--rounded-lg);
        overflow: hidden;
        box-shadow: var(--shadow-3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        background-color: var(--brand-teal-deep);
    }
    
    .hero-slider {
        position: relative;
        width: 100%;
        height: 100%;
    }
    
    .hero-slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1;
    }
    
    .hero-slide.active {
        opacity: 1;
        z-index: 2;
    }
    
    .hero-slide img, 
    .hero-slide video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    
    .hero-slide-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.4) 60%, transparent 100%);
        padding: 24px var(--spacing-lg);
        color: #ffffff;
        z-index: 3;
        text-align: left;
    }
    
    .hero-slide-caption h3 {
        margin: 0 0 6px 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: -0.5px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
    }
    
    .hero-slide-caption p {
        margin: 0;
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.85);
        line-height: 1.4;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.4);
    }
    
    .slider-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(12, 35, 64, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        font-size: 1rem;
        transition: all 0.2s ease;
    }
    
    .slider-btn:hover {
        background: var(--primary);
        border-color: var(--primary);
        transform: translateY(-50%) scale(1.05);
    }
    
    .slider-btn.prev {
        left: 15px;
    }
    
    .slider-btn.next {
        right: 15px;
    }
    
    .slider-indicators {
        position: absolute;
        bottom: 15px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 8px;
        z-index: 10;
    }
    
    .slider-indicators .indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.4);
        border: 1px solid rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .slider-indicators .indicator.active {
        background: var(--primary);
        transform: scale(1.2);
    }
    
    @media (max-width: 992px) {
        .hero-slider-container {
            height: 350px;
        }
    }
    
    @media (max-width: 576px) {
        .hero-slider-container {
            height: 250px;
        }
        .hero-slide-caption h3 {
            font-size: 1rem;
        }
        .hero-slide-caption p {
            font-size: 0.75rem;
        }
    }
</style>
@endsection

@section('content')

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container hero-grid">
            <div class="hero-content">
                <div style="background-color: rgba(242, 92, 63, 0.15); color: var(--primary); font-size: 11px; font-weight: 700; display: inline-block; padding: 4px 12px; border-radius: var(--border-radius); text-transform: uppercase; margin-bottom: 20px; letter-spacing: 1px;">
                    🎓 AKREDITASI B (BAN-PT)
                </div>
                <h2>Membentuk Pemimpin Administrasi & Bisnis Modern</h2>
                <p>Siap berkarier sebagai sekretaris eksekutif, manajer kantor digital, maupun wirausahawan mandiri yang menguasai teknologi informasi di era global.</p>
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <a href="{{ route('public.page.show', 'kurikulum') }}" class="btn btn-primary">
                        <i class="fa-solid fa-graduation-cap"></i> Lihat Kurikulum
                    </a>
                    <a href="{{ route('public.page.show', 'profil-jurusan') }}" class="btn btn-secondary-on-dark">
                        <i class="fa-solid fa-circle-info"></i> Profil Prodi
                    </a>
                </div>
            </div>
            
            <div class="hero-image">
                <div class="hero-slider-container">
                    <div class="hero-slider">
                        @forelse($sliders as $index => $slider)
                            <div class="hero-slide {{ $index === 0 ? 'active' : '' }}">
                                @if($slider->file_type === 'video')
                                    <video src="{{ asset($slider->file_path) }}" autoplay loop muted playsinline></video>
                                @else
                                    <img src="{{ asset($slider->file_path) }}" alt="{{ $slider->title }}">
                                @endif
                                @if($slider->title || $slider->description)
                                    <div class="hero-slide-caption">
                                        @if($slider->title)
                                            <h3>{{ $slider->title }}</h3>
                                        @endif
                                        @if($slider->description)
                                            <p>{{ $slider->description }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @empty
                            <!-- Default fallback slide -->
                            <div class="hero-slide active">
                                <img src="{{ asset('images/hero-bg.png') }}" alt="Default Slide">
                                <div class="hero-slide-caption">
                                    <h3>Administrasi Bisnis LPKIA</h3>
                                    <p>Membentuk Pemimpin Administrasi & Bisnis Modern</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    
                    @if(count($sliders) > 1)
                        <!-- Carousel Navigations -->
                        <button class="slider-btn prev" onclick="moveSlide(-1)">&lt;</button>
                        <button class="slider-btn next" onclick="moveSlide(1)">&gt;</button>
                        
                        <!-- Carousel Indicators -->
                        <div class="slider-indicators">
                            @foreach($sliders as $index => $slider)
                                <span class="indicator {{ $index === 0 ? 'active' : '' }}" onclick="currentSlide({{ $index }})"></span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Banner -->
    <section class="stats-banner">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['total_students'] }}</div>
                    <div class="stat-label">Total Mahasiswa</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['graduates'] }}+</div>
                    <div class="stat-label">Lulusan Sukses</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['employment_rate'] }}</div>
                    <div class="stat-label">Tingkat Penyerapan Kerja</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Kurikulum Berbasis Praktik</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kaprodi Section -->
    <section class="kaprodi-section">
        <div class="container">
            <h2 class="section-title">Kaprodi S1 & D3 Administrasi Bisnis</h2>
            <div class="kaprodi-grid">
                <!-- Kaprodi S1 -->
                <div class="kaprodi-card">
                    <span class="kaprodi-badge">Program S1</span>
                    <div class="kaprodi-image-container">
                        <img src="{{ asset('images/kaprodi-rini.png') }}" alt="Rini Ratnaningsih, S.Sos., M.M.">
                    </div>
                    <div class="kaprodi-info">
                        <h3>Rini Ratnaningsih, S.Sos., M.M.</h3>
                        <p class="kaprodi-role">Ketua Program Studi S1 Administrasi Bisnis</p>
                    </div>
                </div>

                <!-- Kaprodi D3 -->
                <div class="kaprodi-card">
                    <span class="kaprodi-badge">Program D3</span>
                    <div class="kaprodi-image-container">
                        <img src="{{ asset('images/kaprodi-ine.png') }}" alt="Tengku Ine Hendriana, S.Pd, M.M.">
                    </div>
                    <div class="kaprodi-info">
                        <h3>Tengku Ine Hendriana, S.Pd, M.M.</h3>
                        <p class="kaprodi-role">Ketua Program Studi D3 Administrasi Bisnis</p>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Peminatan Section -->
    <section class="peminatan-section">
        <div class="container">
            <h2 class="section-title">Peminatan & Konsentrasi S1 Administrasi Bisnis</h2>
            <div class="peminatan-grid">
                <!-- Peminatan 1 -->
                <div class="peminatan-card">
                    <div class="peminatan-image-container">
                        <img src="{{ asset('images/peminatan-ecommerce.png') }}" alt="Bisnis Digital & E-Commerce">
                    </div>
                    <div class="peminatan-info">
                        <h3>Perdagangan Digital & E-Commerce</h3>
                        <p class="peminatan-desc">
                            Mampu merancang arsitektur operasional bisnis daring dan mengelola ekosistem perdagangan digital dengan mengintegrasikan sistem manajemen rantai pasok (supply chain), otomatisasi transaksi, serta analisis data perilaku konsumen untuk mengoptimalkan efisiensi operasional dan profitabilitas platform e-commerce.
                        </p>
                    </div>
                </div>

                <!-- Peminatan 2 -->
                <div class="peminatan-card">
                    <div class="peminatan-image-container">
                        <img src="{{ asset('images/peminatan-marketing.png') }}" alt="Digital Marketing & MarTech">
                    </div>
                    <div class="peminatan-info">
                        <h3>Pemasaran Digital & MarTech</h3>
                        <p class="peminatan-desc">
                            Mampu merumuskan strategi pemasaran digital berbasis data (data-driven marketing) dengan mengimplementasikan teknik optimasi kanal digital, visualisasi metrik performa pemasaran, serta pemanfaatan teknologi pemasaran (martech) untuk meningkatkan akuisisi, retensi pelanggan, dan ekuitas merek secara terukur.
                        </p>
                    </div>
                </div>

                <!-- Peminatan 3 -->
                <div class="peminatan-card">
                    <div class="peminatan-image-container">
                        <img src="{{ asset('images/peminatan-tech.png') }}" alt="Business Analytics & Tech Solutions">
                    </div>
                    <div class="peminatan-info">
                        <h3>Analisis Bisnis & Solusi IT</h3>
                        <p class="peminatan-desc">
                            Mampu menganalisis, merancang, serta mengimplementasikan solusi bisnis digital dan ekosistem teknologi informasi dengan mengintegrasikan rekayasa perangkat lunak, analisis dan visualisasi data besar (big data), serta infrastruktur teknologi informasi dan komunikasi untuk mendukung transformasi bisnis yang inovatif, efisien, dan berkelanjutan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Profile Section -->
    <section class="content-section">
        <div class="container">
            <div class="grid-2-col" style="align-items: center;">
                <div>
                    <div style="color: var(--brand-teal-mid); font-weight: 700; font-size: 13px; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">
                        TENTANG PROGRAM STUDI
                    </div>
                    <h2 style="font-size: 2.2rem; color: var(--ink); margin-bottom: 20px; line-height: 1.2;">
                        Mempersiapkan Karier di Era <span style="color: var(--brand-teal-mid)">Bisnis Digital</span>
                    </h2>
                    <p style="font-size: 1.05rem; color: var(--slate); margin-bottom: 15px;">
                        Program Studi D3 Administrasi Bisnis LPKIA dirancang secara khusus untuk membekali mahasiswa dengan keterampilan administrasi perkantoran, kesekretariatan modern, kearsipan elektronik, dan pemasaran digital berbasis teknologi informasi.
                    </p>
                    <p style="font-size: 1.05rem; color: var(--slate); margin-bottom: 20px;">
                        Kami menekankan pembelajaran praktikum (learning by doing) agar setiap lulusan tidak hanya menguasai teori bisnis, melainkan terampil mengeksekusi operasional kantor modern dan siap mendirikan usaha mandiri yang kreatif.
                    </p>
                    <a href="{{ route('public.page.show', 'profil-jurusan') }}" class="btn btn-secondary">
                        Pelajari Selengkapnya <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
                <div style="background-color: var(--surface); padding: 40px; border-radius: var(--rounded-lg); border: 1px solid var(--hairline); box-shadow: var(--shadow-1);">
                    <h3 style="font-size: 1.3rem; color: var(--brand-teal-mid); margin-bottom: 20px;"><i class="fa-solid fa-bullseye"></i> Visi Program Studi</h3>
                    <p style="font-style: italic; font-size: 1.1rem; color: var(--ink); line-height: 1.7; margin-bottom: 25px;">
                        "Menjadi Program Studi Administrasi Bisnis yang unggul dan terkemuka di tingkat nasional dalam menghasilkan tenaga profesional administrasi perkantoran digital yang berintegritas, mandiri, dan berjiwa wirausaha kreatif berbasis teknologi informasi pada tahun 2030."
                    </p>
                    <h3 style="font-size: 1.3rem; color: var(--brand-teal-mid); margin-bottom: 15px;"><i class="fa-solid fa-list-check"></i> Misi Utama</h3>
                    <ul style="list-style: none; padding-left: 0; font-size: 0.95rem; color: var(--slate);">
                        <li style="margin-bottom: 8px;"><i class="fa-solid fa-circle-check" style="color:var(--primary-deep); margin-right: 8px;"></i> Menyelenggarakan pendidikan vokasi administrasi bisnis berkualitas tinggi.</li>
                        <li style="margin-bottom: 8px;"><i class="fa-solid fa-circle-check" style="color:var(--primary-deep); margin-right: 8px;"></i> Melaksanakan penelitian terapan dan pengabdian dalam pengembangan kewirausahaan.</li>
                        <li><i class="fa-solid fa-circle-check" style="color:var(--primary-deep); margin-right: 8px;"></i> Membangun kemitraan strategis dengan dunia usaha, industri, dan asosiasi profesi.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Tri-Column Featured Section (Universo Layout) -->
    <section class="featured-columns-section">
        <div class="container featured-columns-grid">
            
            <!-- Column 1: News (Berita Terbaru) -->
            <div class="column-widget">
                <h3>Berita Terbaru</h3>
                <ul class="news-list-widget">
                    @forelse($posts as $post)
                        <li class="news-list-item">
                            <div class="news-list-date">{{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}</div>
                            <h4 class="news-list-title">
                                <a href="{{ route('public.post.show', $post->slug) }}">{{ $post->title }}</a>
                            </h4>
                        </li>
                    @empty
                        <li class="news-list-item">Tidak ada berita terbaru.</li>
                    @endforelse
                </ul>
            </div>

            <!-- Column 2: Events (Agenda & Kegiatan) -->
            <div class="column-widget">
                <h3>Agenda & Event</h3>
                <div class="events-list-widget">
                    <div class="event-item">
                        <div class="event-date-box">
                            <span class="day">15</span>
                            <span class="month">Jul</span>
                        </div>
                        <div class="event-info">
                            <h4 class="event-title"><a href="#">Workshop Business Plan & Tenant Inkubator</a></h4>
                            <span class="event-meta"><i class="fa-solid fa-clock"></i> 09:00 - selesai | Aula LPKIA</span>
                        </div>
                    </div>
                    <div class="event-item">
                        <div class="event-date-box">
                            <span class="day">10</span>
                            <span class="month">Agt</span>
                        </div>
                        <div class="event-info">
                            <h4 class="event-title"><a href="#">Campus Hiring & Rekrutmen Bersama BUMN</a></h4>
                            <span class="event-meta"><i class="fa-solid fa-clock"></i> 08:30 - selesai | Lab Perkantoran</span>
                        </div>
                    </div>
                    <div class="event-item">
                        <div class="event-date-box">
                            <span class="day">01</span>
                            <span class="month">Sep</span>
                        </div>
                        <div class="event-info">
                            <h4 class="event-title"><a href="#">PKKMB & Orientasi Mahasiswa Baru Administrasi Bisnis</a></h4>
                            <span class="event-meta"><i class="fa-solid fa-clock"></i> 07:30 - selesai | Kampus LPKIA</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Column 3: About Program (Tentang LPKIA) -->
            <div class="column-widget">
                <h3>Tentang Program Studi</h3>
                <img src="{{ asset('images/logo.png') }}" alt="Tentang Administrasi Bisnis LPKIA" class="about-widget-thumbnail">
                <p class="about-widget-text">
                    Selamat datang di Program Studi D3 Administrasi Bisnis LPKIA Bandung. Kami mempersiapkan tenaga ahli administrasi perkantoran unggul dan wirausahawan mandiri yang menguasai perangkat digital.
                </p>
                <a href="{{ route('public.page.show', 'profil-jurusan') }}" class="about-widget-link">
                    Pelajari Selengkapnya <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>

        </div>
    </section>



    <!-- Faculty/Lecturer Section -->
    <section class="content-section" id="dosen" style="background-color: var(--surface); border-top: 1px solid var(--hairline); border-bottom: 1px solid var(--hairline);">
        <div class="container">
            <h2 class="section-title">Dosen Ahli & Pengajar</h2>
            <p style="text-align: center; color: var(--steel); max-width: 600px; margin: -20px auto 40px; font-size: 0.95rem;">
                Dosen kami merupakan praktisi bisnis bersertifikat kompetensi profesi dan akademisi berpengalaman di bidang administrasi bisnis.
            </p>

            <!-- Interactive Filter tabs -->
            <div class="filter-tabs">
                <button type="button" class="filter-btn active" onclick="filterLecturers('all', this)">Semua Bidang</button>
                <button type="button" class="filter-btn" onclick="filterLecturers('office', this)">Administrasi Perkantoran</button>
                <button type="button" class="filter-btn" onclick="filterLecturers('entrepreneur', this)">Kewirausahaan</button>
                <button type="button" class="filter-btn" onclick="filterLecturers('digital', this)">Bisnis Digital</button>
            </div>

            <!-- Lecturers Grid -->
            <div class="lecturer-grid" id="lecturerGrid">
                @forelse($lecturers as $lecturer)
                    <div class="lecturer-card" data-expert="{{ $lecturer->expertise }}">
                        @if($lecturer->photo)
                            <img src="{{ asset($lecturer->photo) }}" alt="{{ $lecturer->name }}" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; display: inline-block; border: 2px solid var(--hairline-strong);">
                        @else
                            <i class="fa-solid fa-user-circle fa-4x" style="color: var(--brand-teal-deep); margin-bottom: 15px; display: block;"></i>
                        @endif
                        <h4>{{ $lecturer->name }}</h4>
                        <span class="field">
                            @if($lecturer->expertise === 'office')
                                Adm Perkantoran
                            @elseif($lecturer->expertise === 'entrepreneur')
                                Kewirausahaan
                            @elseif($lecturer->expertise === 'digital')
                                Bisnis Digital
                            @else
                                Dosen Pengajar
                            @endif
                        </span>
                        <div style="font-size: 11px; color: var(--stone); margin-top: 6px;">NIDN: {{ $lecturer->nidn ?? '-' }}</div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; color: var(--muted); padding: 30px;">
                        Data dosen belum tersedia.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <h2 class="section-title">Testimoni Alumni Sukses</h2>
            <p style="text-align: center; color: var(--steel); max-width: 600px; margin: -20px auto 40px; font-size: 0.95rem;">
                Dengarkan langsung cerita sukses alumni kami yang telah berkarier di berbagai industri terkemuka setelah lulus dari Administrasi Bisnis LPKIA.
            </p>

            <div class="testimonials-grid">
                <!-- Testimonial 1 -->
                <div class="testimonial-card">
                    <div>
                        <div class="testimonial-stars">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <p class="testimonial-content">
                            "Kurikulum praktis di AB LPKIA sangat relevan dengan dunia kerja. Saya belajar banyak tentang digital office administration dan korespondensi bisnis modern yang langsung saya terapkan sehari-hari."
                        </p>
                    </div>
                    <div class="testimonial-author">
                        <img src="{{ asset('images/alumni-1.png') }}" alt="Siti Rahma" class="testimonial-avatar">
                        <div class="testimonial-info">
                            <h4>Siti Rahma, A.Md.AB.</h4>
                            <p>Lulusan 2022 | Executive Secretary di PT Astra International</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="testimonial-card">
                    <div>
                        <div class="testimonial-stars">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <p class="testimonial-content">
                            "Kombinasi bisnis, teknologi, dan kewirausahaan yang diajarkan membuat saya sangat percaya diri untuk merintis karier di industri digital marketing maupun membangun bisnis sendiri."
                        </p>
                    </div>
                    <div class="testimonial-author">
                        <img src="{{ asset('images/alumni-2.png') }}" alt="Budi Santoso" class="testimonial-avatar">
                        <div class="testimonial-info">
                            <h4>Budi Santoso, A.Md.AB.</h4>
                            <p>Lulusan 2021 | Digital Marketing Specialist di Tokopedia</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="testimonial-card">
                    <div>
                        <div class="testimonial-stars">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <p class="testimonial-content">
                            "Dosen-dosennya adalah praktisi yang luar biasa. Dukungan dari inkubator bisnis dan bimbingan magang membantu saya mendapatkan pekerjaan sebelum resmi diwisuda."
                        </p>
                    </div>
                    <div class="testimonial-author">
                        <img src="{{ asset('images/alumni-3.png') }}" alt="Dian Lestari" class="testimonial-avatar">
                        <div class="testimonial-info">
                            <h4>Dian Lestari, A.Md.AB.</h4>
                            <p>Lulusan 2023 | Office Administrator di Bank Mandiri</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Academics, Downloads & Schedule Table -->
    <section class="content-section" id="unduh-dokumen">
        <div class="container">
            <div class="grid-2-col" style="margin-bottom: 60px; align-items: center;">
                <!-- Download Area -->
                <div>
                    <h3 style="font-size: 1.5rem; color: var(--ink); margin-bottom: 15px;">
                        <i class="fa-solid fa-circle-down"></i> Area Unduhan Dokumen
                    </h3>
                    <p style="color: var(--slate); font-size: 0.95rem; margin-bottom: 25px;">
                        Unduh berkas kurikulum resmi, panduan tugas akhir, serta silabus mata kuliah Program Studi Administrasi Bisnis LPKIA.
                    </p>
                    
                    <div class="download-grid">
                        <div class="download-card">
                            <div class="download-info">
                                <i class="fa-solid fa-file-pdf"></i>
                                <div>
                                    <h5>Kurikulum AB LPKIA 2026.pdf</h5>
                                    <p>Ukuran: 1.4 MB | PDF Document</p>
                                </div>
                            </div>
                            <a href="#" onclick="alert('Mengunduh dokumen Kurikulum AB LPKIA 2026.pdf (Simulasi)')" class="btn btn-primary btn-sm btn-icon" title="Download">
                                <i class="fa-solid fa-download"></i>
                            </a>
                        </div>

                        <div class="download-card">
                            <div class="download-info">
                                <i class="fa-solid fa-file-pdf"></i>
                                <div>
                                    <h5>Panduan Magang Kerja AB.pdf</h5>
                                    <p>Ukuran: 920 KB | PDF Document</p>
                                </div>
                            </div>
                            <a href="#" onclick="alert('Mengunduh dokumen Panduan Magang Kerja AB.pdf (Simulasi)')" class="btn btn-primary btn-sm btn-icon" title="Download">
                                <i class="fa-solid fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Academic Info Notification (Teal Highlight Box) -->
                <div style="background-color: var(--brand-teal-deep); color: white; padding: 40px; border-radius: var(--rounded-lg); border: 2px solid var(--primary); box-shadow: var(--shadow-3); display: flex; flex-direction: column; justify-content: center;">
                    <div style="background-color: var(--primary); color: var(--on-primary); font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: var(--rounded-full); align-self: flex-start; margin-bottom: 15px; text-transform: uppercase;">
                        Pengumuman Terkini
                    </div>
                    <h4 style="color: var(--primary); font-size: 1.25rem; margin-bottom: 15px; font-weight: 600;">
                        Pembagian Kelompok Simulasi Perkantoran
                    </h4>
                    <p style="font-size: 0.95rem; line-height: 1.7; margin-bottom: 20px; color: var(--on-dark-muted);">
                        Kelompok simulasi perkantoran untuk Semester Ganjil TA 2026/2027 telah diumumkan di mading utama prodi dan sistem informasi mahasiswa. Harap melakukan registrasi kelompok paling lambat <strong>20 Agustus 2026</strong>.
                    </p>
                    <a href="#jadwal-kelas" class="btn btn-primary btn-sm" style="align-self: flex-start;">
                        <i class="fa-solid fa-calendar-day"></i> Lihat Jadwal Kelas
                    </a>
                </div>
            </div>

            <!-- Class Schedule Table -->
            <div id="jadwal-kelas" style="margin-top: var(--spacing-xxxl);">
                <h2 class="section-title">Jadwal Kelas Unggulan</h2>
                <p style="text-align: center; color: var(--slate); max-width: 600px; margin: -20px auto 30px; font-size: 0.95rem;">
                    Jadwal perkuliahan praktikum mingguan mahasiswa Administrasi Bisnis di berbagai laboratorium simulasi dan komputer.
                </p>

                <div class="table-wrapper">
                    <table class="responsive-table">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Jam Perkuliahan</th>
                                <th>Mata Kuliah Unggulan</th>
                                <th>Dosen Pengampu</th>
                                <th>Lokasi Laboratorium</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-weight: 700; color: var(--brand-teal-deep);">Senin</td>
                                <td>08:00 - 10:30</td>
                                <td style="font-weight: 600;">Aplikasi Perkantoran Digital</td>
                                <td>Dra. Endang Setyowati, M.M.</td>
                                <td><span class="badge badge-success">Lab Komputer Bisnis 3</span></td>
                            </tr>
                            <tr>
                                <td style="font-weight: 700; color: var(--brand-teal-deep);">Selasa</td>
                                <td>10:40 - 13:10</td>
                                <td style="font-weight: 600;">Korespondensi Bisnis</td>
                                <td>Rina Herlina, S.Sos., M.AB.</td>
                                <td><span class="badge badge-secondary">Ruang Teori 402</span></td>
                            </tr>
                            <tr>
                                <td style="font-weight: 700; color: var(--brand-teal-deep);">Rabu</td>
                                <td>13:30 - 16:00</td>
                                <td style="font-weight: 600;">Manajemen Kearsipan Elektronik</td>
                                <td>Rina Herlina, S.Sos., M.AB.</td>
                                <td><span class="badge badge-success">Lab Kearsipan Digital</span></td>
                            </tr>
                            <tr>
                                <td style="font-weight: 700; color: var(--brand-teal-deep);">Kamis</td>
                                <td>08:00 - 10:30</td>
                                <td style="font-weight: 600;">Pemasaran Digital & E-Commerce</td>
                                <td>Yusuf Maulana, M.B.A.</td>
                                <td><span class="badge badge-success">Lab Komputer Bisnis 1</span></td>
                            </tr>
                            <tr>
                                <td style="font-weight: 700; color: var(--brand-teal-deep);">Jumat</td>
                                <td>10:00 - 12:30</td>
                                <td style="font-weight: 600;">Kewirausahaan Mandiri</td>
                                <td>Budi Setiawan, S.E., M.M.</td>
                                <td><span class="badge badge-secondary">Inkubator Bisnis</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest News / Articles -->
    <section class="content-section" id="berita" style="background-color: var(--surface); border-top: 1px solid var(--hairline);">
        <div class="container">
            <h2 class="section-title">Berita & Kegiatan Terbaru</h2>
            <div class="grid-3-col">
                @forelse($posts as $post)
                    <article class="post-card">
                        @if($post->featured_image)
                            <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="post-card-img">
                        @else
                            <div class="post-card-img" style="display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.25);">
                                <i class="fa-solid fa-newspaper fa-3x"></i>
                                <span class="post-card-category">{{ $post->category->name }}</span>
                            </div>
                        @endif
                        <div class="post-card-body">
                            <div class="post-card-meta">
                                <span><i class="fa-solid fa-calendar-days"></i> {{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}</span>
                                <span><i class="fa-solid fa-user"></i> {{ $post->author->name }}</span>
                            </div>
                            <h3 class="post-card-title">{{ \Illuminate\Support\Str::limit($post->title, 55) }}</h3>
                            <p class="post-card-excerpt">{!! \Illuminate\Support\Str::limit(strip_tags($post->content), 120) !!}</p>
                            <a href="{{ route('public.post.show', $post->slug) }}" class="post-card-link">
                                Baca Selengkapnya <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--slate);">
                        <i class="fa-regular fa-folder-open fa-3x" style="margin-bottom: 15px; display: block; color: var(--muted);"></i>
                        Belum ada berita yang diterbitkan saat ini.
                    </div>
                @endforelse
            </div>
            
            <div style="text-align: center; margin-top: 40px;">
                <a href="{{ route('public.posts') }}" class="btn btn-secondary">
                    Lihat Semua Berita <i class="fa-solid fa-angles-right"></i>
                </a>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // Hero Slider Logic
    let currentSlideIndex = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const indicators = document.querySelectorAll('.slider-indicators .indicator');
    let slideInterval;

    function showSlide(index) {
        if (slides.length === 0) return;
        
        if (index >= slides.length) {
            currentSlideIndex = 0;
        } else if (index < 0) {
            currentSlideIndex = slides.length - 1;
        } else {
            currentSlideIndex = index;
        }
        
        slides.forEach((slide, idx) => {
            if (idx === currentSlideIndex) {
                slide.classList.add('active');
                
                // Play video if the active slide is a video
                const video = slide.querySelector('video');
                if (video) {
                    video.currentTime = 0;
                    video.play().catch(e => console.log('Video autoplay interrupted:', e));
                }
            } else {
                slide.classList.remove('active');
                
                // Pause video if not active
                const video = slide.querySelector('video');
                if (video) {
                    video.pause();
                }
            }
        });
        
        indicators.forEach((indicator, idx) => {
            if (idx === currentSlideIndex) {
                indicator.classList.add('active');
            } else {
                indicator.classList.remove('active');
            }
        });
    }

    function moveSlide(direction) {
        showSlide(currentSlideIndex + direction);
        resetSlideTimer();
    }

    function currentSlide(index) {
        showSlide(index);
        resetSlideTimer();
    }

    function startSlideTimer() {
        if (slides.length > 1) {
            slideInterval = setInterval(() => {
                showSlide(currentSlideIndex + 1);
            }, 6000); // Change slide every 6 seconds
        }
    }

    function resetSlideTimer() {
        clearInterval(slideInterval);
        startSlideTimer();
    }

    // Faculty Filter Logic
    function filterLecturers(category, button) {
        // Toggle Active Button
        const buttons = document.querySelectorAll('.filter-btn');
        buttons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');

        // Toggle Cards Visibility
        const cards = document.querySelectorAll('#lecturerGrid .lecturer-card');
        cards.forEach(card => {
            const expertType = card.getAttribute('data-expert');
            if (category === 'all' || expertType === category) {
                card.style.display = 'block';
                card.style.opacity = '0';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transition = 'opacity 0.3s ease';
                }, 50);
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Initialize components
    document.addEventListener('DOMContentLoaded', () => {
        showSlide(currentSlideIndex);
        startSlideTimer();
    });
</script>
@endsection

