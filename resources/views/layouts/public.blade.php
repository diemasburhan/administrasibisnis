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
    
    <!-- Dynamic Theme and Font Customizer Style Block -->
    @php
        $selectedFont = \App\Models\Setting::get('theme_font_family', 'Plus Jakarta Sans');
        $fontStackMap = [
            'Plus Jakarta Sans' => "'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif",
            'Inter' => "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif",
            'Roboto' => "'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif",
            'Outfit' => "'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif",
            'Playfair Display' => "'Playfair Display', Georgia, serif"
        ];
        $fontStack = $fontStackMap[$selectedFont] ?? $fontStackMap['Plus Jakarta Sans'];
        
        $fontUrlMap = [
            'Inter' => 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap',
            'Roboto' => 'https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,400&display=swap',
            'Outfit' => 'https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap',
            'Playfair Display' => 'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap'
        ];
    @endphp
    
    @if(isset($fontUrlMap[$selectedFont]))
        <link href="{{ $fontUrlMap[$selectedFont] }}" rel="stylesheet">
    @endif
    
    <style>
        :root {
            --primary: {{ \App\Models\Setting::get('theme_primary_color', '#f25c3f') }};
            --primary-deep: {{ \App\Models\Setting::get('theme_primary_deep_color', '#e14a2c') }};
            --primary-pressed: {{ \App\Models\Setting::get('theme_primary_deep_color', '#e14a2c') }};
            --brand-teal-deep: {{ \App\Models\Setting::get('theme_brand_teal_deep_color', '#0c2340') }};
            --brand-teal: {{ \App\Models\Setting::get('theme_brand_teal_color', '#0f2d52') }};
            --brand-teal-mid: {{ \App\Models\Setting::get('theme_brand_teal_mid_color', '#164275') }};
            --font-family: {!! $fontStack !!};
        }
        
        {!! \App\Models\Setting::get('theme_custom_css', '') !!}
        
        /* Embedded widgets styles */
        .content-gallery-grid {
            display: grid;
            gap: 15px;
            margin: 25px 0;
        }
        .content-gallery-grid.cols-2 { grid-template-columns: repeat(2, 1fr); }
        .content-gallery-grid.cols-3 { grid-template-columns: repeat(3, 1fr); }
        .content-gallery-grid.cols-4 { grid-template-columns: repeat(4, 1fr); }
        .content-gallery-grid img {
            width: 100%;
            border-radius: 8px;
            object-fit: cover;
            aspect-ratio: 4/3;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .content-gallery-grid img:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
        .responsive-video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            max-width: 100%;
            margin: 25px 0;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .responsive-video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .callout {
            padding: 15px 20px;
            margin: 25px 0;
            border-left: 5px solid #ccc;
            border-radius: 4px;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        .callout.callout-info { border-left-color: #3b82f6; background-color: #eff6ff; color: #1e3a8a; }
        .callout.callout-success { border-left-color: #10b981; background-color: #ecfdf5; color: #065f46; }
        .callout.callout-warning { border-left-color: #f59e0b; background-color: #fffbeb; color: #92400e; }
        .callout.callout-danger { border-left-color: #ef4444; background-color: #fef2f2; color: #991b1b; }
        
        @media(max-width: 768px) {
            .content-gallery-grid.cols-3, .content-gallery-grid.cols-4 { grid-template-columns: repeat(2, 1fr); }
        }
        @media(max-width: 480px) {
            .content-gallery-grid { grid-template-columns: 1fr !important; }
        }
    </style>

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
                @php
                    $menuRaw = \App\Models\Setting::get('site_menu');
                    $menuItems = json_decode($menuRaw, true);
                    if (!is_array($menuItems) || empty($menuItems)) {
                        $menuItems = [
                            ['label' => 'Beranda', 'url' => route('home')],
                            ['label' => 'Profil', 'url' => '#', 'children' => [
                                ['label' => 'Profil Prodi', 'url' => route('public.page.show', 'profil-jurusan'), 'icon' => 'fa-circle-info'],
                                ['label' => 'Visi & Misi', 'url' => route('public.page.show', 'visi-misi'), 'icon' => 'fa-bullseye'],
                                ['label' => 'Fasilitas Lab', 'url' => route('public.page.show', 'fasilitas'), 'icon' => 'fa-building-user']
                            ]],
                            ['label' => 'Kurikulum', 'url' => route('public.page.show', 'kurikulum')],
                            ['label' => 'Dosen', 'url' => route('home') . '#dosen'],
                            ['label' => 'Berita', 'url' => route('public.posts')],
                            ['label' => 'Kontak', 'url' => route('public.page.show', 'kontak')]
                        ];
                    }
                @endphp

                @foreach($menuItems as $item)
                    @if(isset($item['children']) && count($item['children']) > 0)
                        <!-- Dropdown -->
                        <div class="nav-dropdown">
                            <a href="#" class="nav-link">
                                {{ $item['label'] }} <i class="fa-solid fa-chevron-down" style="font-size: 0.7rem; margin-left: 2px;"></i>
                            </a>
                            <div class="dropdown-content">
                                @foreach($item['children'] as $child)
                                    @php
                                        $icon = 'fa-link';
                                        if (isset($child['icon'])) {
                                            $icon = $child['icon'];
                                        } elseif (strpos(strtolower($child['url']), 'profil') !== false || strpos(strtolower($child['url']), 'tentang') !== false) {
                                            $icon = 'fa-circle-info';
                                        } elseif (strpos(strtolower($child['url']), 'visi') !== false) {
                                            $icon = 'fa-bullseye';
                                        } elseif (strpos(strtolower($child['url']), 'fasilitas') !== false) {
                                            $icon = 'fa-building-user';
                                        }
                                    @endphp
                                    <a href="{{ $child['url'] }}"><i class="fa-solid {{ $icon }}"></i> {{ $child['label'] }}</a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        @php
                            $isActive = false;
                            $currentUrl = request()->getRequestUri();
                            if ($item['url'] === '/' && request()->is('/')) {
                                $isActive = true;
                            } elseif ($item['url'] !== '/' && $item['url'] !== '#') {
                                if (request()->is(ltrim($item['url'], '/')) || request()->fullUrlIs($item['url']) || strpos($currentUrl, $item['url']) !== false) {
                                    $isActive = true;
                                }
                            }
                        @endphp
                        <a href="{{ $item['url'] }}" class="nav-link {{ $isActive ? 'active' : '' }}">{{ $item['label'] }}</a>
                    @endif
                @endforeach
                
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
            <p>&copy; {{ date('Y') }} Program Studi Administrasi Bisnis LPKIA. Hak Cipta Dilindungi.</p>
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
