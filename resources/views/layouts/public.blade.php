<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Administrasi Bisnis LPKIA')</title>
    <!-- Load Plus Jakarta Sans (Geometric fallbacks for Euclid) and Source Code Pro -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Source+Code+Pro:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @yield('styles')
</head>
<body>

    <!-- Top Utility Info Bar -->
    <div class="top-utility-bar">
        <div class="top-utility-container">
            <div class="top-utility-contact">
                <a href="tel:0227564200"><i class="fa-solid fa-phone" style="color: var(--primary);"></i> (022) 7564200</a>
                <span style="color: rgba(255,255,255,0.2);">|</span>
                <a href="mailto:admbisnis@lpkia.ac.id"><i class="fa-solid fa-envelope" style="color: var(--primary);"></i> admbisnis@lpkia.ac.id</a>
            </div>
            <div class="top-utility-links">
                <a href="#">Prospective Students</a>
                <a href="#">Current Students</a>
                <a href="#">Faculty & Staff</a>
                <a href="#">Alumni</a>
            </div>
        </div>
    </div>

    <!-- Header Navigation -->
    <header class="site-header">
        <div class="container header-container">
            <a href="{{ route('home') }}" class="logo-section" style="display: flex; align-items: center; text-decoration: none;">
                <img src="{{ asset('images/logo-administrasi-bisnis.png') }}" alt="Logo Administrasi Bisnis LPKIA" style="height: 42px; width: auto; display: block;">
            </a>
            
            <button class="menu-toggle" id="menuToggle" aria-label="Toggle Menu">
                <i class="fa-solid fa-bars"></i>
            </button>

            <nav class="nav-menu" id="navMenu">
                <a href="{{ route('home') }}" class="nav-link {{ Route::is('home') ? 'active' : '' }}">Beranda</a>
                
                <!-- Dropdown Profil -->
                <div class="nav-dropdown">
                    <a href="#" class="nav-link">
                        Profil <i class="fa-solid fa-chevron-down" style="font-size: 0.7rem; margin-left: 2px;"></i>
                    </a>
                    <div class="dropdown-content">
                        <a href="{{ route('public.page.show', 'profil-jurusan') }}"><i class="fa-solid fa-circle-info"></i> Profil Prodi</a>
                        <a href="{{ route('public.page.show', 'visi-misi') }}"><i class="fa-solid fa-bullseye"></i> Visi & Misi</a>
                        <a href="{{ route('public.page.show', 'fasilitas') }}"><i class="fa-solid fa-building-user"></i> Fasilitas Lab</a>
                    </div>
                </div>

                <a href="{{ route('public.page.show', 'kurikulum') }}" class="nav-link {{ Request::is('page/kurikulum') ? 'active' : '' }}">Kurikulum</a>
                <a href="{{ route('home') }}#dosen" class="nav-link">Dosen</a>
                <a href="{{ route('public.posts') }}" class="nav-link {{ Route::is('public.posts') || Request::is('post/*') ? 'active' : '' }}">Berita</a>
                <a href="{{ route('public.page.show', 'kontak') }}" class="nav-link {{ Request::is('page/kontak') ? 'active' : '' }}">Kontak</a>
                
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-gauge"></i> Admin Panel
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-right-to-bracket"></i> Login Admin
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Content Area -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container footer-grid">
            <div class="footer-widget">
                <h3>Administrasi Bisnis LPKIA</h3>
                <p>Membentuk tenaga profesional administrasi perkantoran digital, sekretaris modern, dan wirausahawan muda kreatif yang unggul dan siap kerja berbasis teknologi informasi.</p>
                <div class="social-links">
                    <a href="https://facebook.com/lpkia" target="_blank" class="social-icon" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://instagram.com/lpkia" target="_blank" class="social-icon" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://youtube.com/lpkia" target="_blank" class="social-icon" aria-label="Youtube"><i class="fa-brands fa-youtube"></i></a>
                    <a href="https://linkedin.com/school/lpkia" target="_blank" class="social-icon" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </div>
            
            <div class="footer-widget">
                <h3>Akses Cepat</h3>
                <ul>
                    <li><a href="{{ route('home') }}"><i class="fa-solid fa-chevron-right"></i> Beranda</a></li>
                    <li><a href="{{ route('public.page.show', 'profil-jurusan') }}"><i class="fa-solid fa-chevron-right"></i> Profil Program Studi</a></li>
                    <li><a href="{{ route('public.page.show', 'kurikulum') }}"><i class="fa-solid fa-chevron-right"></i> Kurikulum Pendidikan</a></li>
                    <li><a href="{{ route('public.page.show', 'fasilitas') }}"><i class="fa-solid fa-chevron-right"></i> Fasilitas Laboratorium</a></li>
                </ul>
            </div>
            
            <div class="footer-widget">
                <h3>Kontak & Informasi</h3>
                <p><i class="fa-solid fa-map-location-dot"></i> {{ \App\Models\Setting::get('site_address', 'Jl. Soekarno-Hatta No. 456, Bandung') }}</p>
                <p><i class="fa-solid fa-phone"></i> {{ \App\Models\Setting::get('site_phone', '(022) 7564200') }}</p>
                <p><i class="fa-solid fa-envelope"></i> {{ \App\Models\Setting::get('site_email', 'admbisnis@lpkia.ac.id') }}</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Program Studi D3 Administrasi Bisnis LPKIA. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <!-- Mobile Drawer Script -->
    <script>
        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');

        menuToggle.addEventListener('click', () => {
            navMenu.style.display = navMenu.style.display === 'flex' ? 'none' : 'flex';
            if (navMenu.style.display === 'flex') {
                navMenu.style.flexDirection = 'column';
                navMenu.style.position = 'absolute';
                navMenu.style.top = '80px';
                navMenu.style.left = '0';
                navMenu.style.width = '100%';
                navMenu.style.backgroundColor = 'var(--bg-white)';
                navMenu.style.padding = '20px';
                navMenu.style.gap = '15px';
                navMenu.style.borderBottom = '3px solid var(--primary)';
                navMenu.style.boxShadow = 'var(--shadow-md)';
            }
        });

        // Hide mobile menu on resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                navMenu.style.display = 'flex';
                navMenu.style.flexDirection = 'row';
                navMenu.style.position = 'static';
                navMenu.style.padding = '0';
                navMenu.style.gap = '28px';
                navMenu.style.backgroundColor = 'transparent';
                navMenu.style.borderBottom = 'none';
                navMenu.style.boxShadow = 'none';
            } else {
                navMenu.style.display = 'none';
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
