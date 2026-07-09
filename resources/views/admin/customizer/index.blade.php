@extends('layouts.admin')

@section('title', 'Customizer Beranda - Admin Panel')
@section('page_title', 'Customizer Halaman Utama')

@section('styles')
<style>
    .customizer-container {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 25px;
        margin-top: 15px;
    }
    .customizer-tabs {
        background-color: var(--bg-white);
        border: 1px solid var(--hairline);
        border-radius: var(--rounded-lg);
        padding: 15px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        height: fit-content;
        box-shadow: var(--shadow-sm);
    }
    .customizer-tab-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        background: none;
        border: none;
        border-radius: var(--rounded-md);
        color: var(--text-dark);
        font-weight: 500;
        text-align: left;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .customizer-tab-btn:hover {
        background-color: var(--bg-light);
        color: var(--primary);
    }
    .customizer-tab-btn.active {
        background-color: var(--primary);
        color: var(--on-primary);
    }
    .customizer-tab-btn i {
        font-size: 1.1rem;
        width: 20px;
        text-align: center;
    }
    .customizer-panel {
        background-color: var(--bg-white);
        border: 1px solid var(--hairline);
        border-radius: var(--rounded-lg);
        padding: 30px;
        box-shadow: var(--shadow-sm);
        display: none;
    }
    .customizer-panel.active {
        display: block;
    }
    .customizer-header {
        border-bottom: 1px solid var(--hairline);
        padding-bottom: 15px;
        margin-bottom: 25px;
    }
    .customizer-header h3 {
        font-size: 1.4rem;
        color: var(--primary-deep);
        margin: 0;
    }
    .customizer-header p {
        margin: 5px 0 0 0;
        color: var(--muted);
        font-size: 0.9rem;
    }
    /* Section Order / Sortable styling */
    .section-order-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 20px;
    }
    .section-order-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 20px;
        background-color: var(--bg-light);
        border: 1px solid var(--hairline-strong);
        border-radius: var(--rounded-md);
        font-weight: 600;
        color: var(--text-dark);
    }
    .section-order-item .actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-order-item .btn-move {
        background-color: var(--bg-white);
        border: 1px solid var(--hairline-strong);
        border-radius: 4px;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--text-dark);
        transition: all 0.15s ease;
    }
    .section-order-item .btn-move:hover:not(:disabled) {
        background-color: var(--primary);
        color: var(--on-primary);
        border-color: var(--primary);
    }
    .section-order-item .btn-move:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }
    /* Switch toggle styling */
    .switch-container {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
    }
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: var(--primary);
    }
    input:focus + .slider {
        box-shadow: 0 0 1px var(--primary);
    }
    input:checked + .slider:before {
        transform: translateX(24px);
    }
    /* List items builder styling */
    .list-builder-item {
        background-color: var(--bg-light);
        border: 1px solid var(--hairline);
        border-radius: var(--rounded-md);
        padding: 15px;
        margin-bottom: 12px;
        position: relative;
    }
    .list-builder-item .btn-remove {
        position: absolute;
        top: 15px;
        right: 15px;
        color: var(--danger);
        cursor: pointer;
        background: none;
        border: none;
        font-size: 1.1rem;
    }
    .list-builder-item .grid-fields {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
        padding-right: 30px;
    }
    .list-builder-item .grid-fields-half {
        display: grid;
        grid-template-columns: 100px 1fr;
        gap: 12px;
        padding-right: 30px;
    }
    .btn-add-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
    }
</style>
@endsection

@section('content')
<form action="{{ route('admin.customizer.update') }}" method="POST" id="customizerForm">
    @csrf

    <!-- Hidden Inputs to store order and visibility JSON -->
    <input type="hidden" name="sections_order" id="sectionsOrderInput" value="{{ json_encode($sectionsOrder) }}">
    <input type="hidden" name="sections_visibility" id="sectionsVisibilityInput" value="{{ json_encode($sectionsVisibility) }}">

    <div class="customizer-container">
        <!-- Sidebar Tabs -->
        <aside class="customizer-tabs">
            <button type="button" class="customizer-tab-btn active" onclick="switchTab('general', this)">
                <i class="fa-solid fa-desktop"></i> Umum / Tampilan
            </button>
            <button type="button" class="customizer-tab-btn" onclick="switchTab('hero', this)">
                <i class="fa-solid fa-circle-play"></i> Hero Section
            </button>
            <button type="button" class="customizer-tab-btn" onclick="switchTab('tri_column', this)">
                <i class="fa-solid fa-columns"></i> Tri-Column Section
            </button>
            <button type="button" class="customizer-tab-btn" onclick="switchTab('about', this)">
                <i class="fa-solid fa-address-card"></i> About & Visi Misi
            </button>
            <button type="button" class="customizer-tab-btn" onclick="switchTab('downloads', this)">
                <i class="fa-solid fa-download"></i> Unduhan & Pengumuman
            </button>
            <button type="button" class="customizer-tab-btn" onclick="switchTab('theme', this)">
                <i class="fa-solid fa-palette"></i> Warna & Font (Tema)
            </button>
            <button type="button" class="customizer-tab-btn" onclick="switchTab('order', this)">
                <i class="fa-solid fa-sort"></i> Susunan Section
            </button>
            
            <div style="margin-top: 20px; border-top: 1px solid var(--hairline); padding-top: 15px;">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Semua
                </button>
            </div>
        </aside>

        <!-- Panels -->
        <main class="customizer-panels-wrapper">
            <!-- PANEL: GENERAL -->
            <div class="customizer-panel active" id="panel-general">
                <div class="customizer-header">
                    <h3>Pengaturan Umum Beranda</h3>
                    <p>Atur apa yang ditampilkan pada halaman utama website Anda.</p>
                </div>

                <div class="form-group">
                    <label for="homepage_type">Halaman Utama Menampilkan</label>
                    <select name="homepage_type" id="homepage_type" class="form-control" onchange="toggleHomepageType(this.value)">
                        <option value="default" {{ $homepageType === 'default' ? 'selected' : '' }}>Layout Beranda Default (Customizable Sections)</option>
                        <option value="static_page" {{ $homepageType === 'static_page' ? 'selected' : '' }}>Halaman Statis (Static Page)</option>
                    </select>
                </div>

                <div class="form-group" id="staticPageSelectGroup" style="display: {{ $homepageType === 'static_page' ? 'block' : 'none' }};">
                    <label for="homepage_static_page_id">Pilih Halaman Statis</label>
                    <select name="homepage_static_page_id" id="homepage_static_page_id" class="form-control">
                        <option value="">-- Pilih Halaman --</option>
                        @foreach($pages as $page)
                            <option value="{{ $page->id }}" {{ $homepageStaticPageId == $page->id ? 'selected' : '' }}>{{ $page->title }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Halaman yang dipilih akan dimuat di alamat utama website Anda (/).</small>
                </div>
            </div>

            <!-- PANEL: HERO -->
            <div class="customizer-panel" id="panel-hero">
                <div class="customizer-header">
                    <h3>Hero Section</h3>
                    <p>Sesuaikan bagian banner paling atas (Hero) pada halaman utama.</p>
                </div>

                <div class="form-group">
                    <label for="hero_badge">Badge Teks</label>
                    <input type="text" name="hero[badge]" id="hero_badge" class="form-control" value="{{ $hero['badge'] }}">
                </div>

                <div class="form-group">
                    <label for="hero_title">Judul Utama</label>
                    <input type="text" name="hero[title]" id="hero_title" class="form-control" value="{{ $hero['title'] }}">
                </div>

                <div class="form-group">
                    <label for="hero_subtitle">Sub-Judul / Deskripsi</label>
                    <textarea name="hero[subtitle]" id="hero_subtitle" rows="3" class="form-control">{{ $hero['subtitle'] }}</textarea>
                </div>

                <div class="grid-2-col" style="gap: 15px; margin-bottom: 15px;">
                    <div class="form-group">
                        <label for="hero_btn1_label">Tombol 1 Label</label>
                        <input type="text" name="hero[btn1_label]" id="hero_btn1_label" class="form-control" value="{{ $hero['btn1_label'] }}">
                    </div>
                    <div class="form-group">
                        <label for="hero_btn1_url">Tombol 1 URL</label>
                        <input type="text" name="hero[btn1_url]" id="hero_btn1_url" class="form-control" value="{{ $hero['btn1_url'] }}">
                    </div>
                </div>

                <div class="grid-2-col" style="gap: 15px; margin-bottom: 15px;">
                    <div class="form-group">
                        <label for="hero_btn2_label">Tombol 2 Label</label>
                        <input type="text" name="hero[btn2_label]" id="hero_btn2_label" class="form-control" value="{{ $hero['btn2_label'] }}">
                    </div>
                    <div class="form-group">
                        <label for="hero_btn2_url">Tombol 2 URL</label>
                        <input type="text" name="hero[btn2_url]" id="hero_btn2_url" class="form-control" value="{{ $hero['btn2_url'] }}">
                    </div>
                </div>

                <div class="form-group">
                    <div class="switch-container">
                        <label class="switch">
                            <input type="checkbox" name="hero[show_inquiry]" value="1" {{ $hero['show_inquiry'] ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                        <span style="font-weight: 500;">Tampilkan Formulir Pendaftaran (Join the Community) di Sebelah Kanan</span>
                    </div>
                </div>
            </div>

            <!-- PANEL: TRI-COLUMN -->
            <div class="customizer-panel" id="panel-tri_column">
                <div class="customizer-header">
                    <h3>Tri-Column Featured Section</h3>
                    <p>Kelola konten 3 kolom info (Berita, Event, & Tentang Program Studi).</p>
                </div>

                <h4 style="color: var(--primary); margin-top: 20px; margin-bottom: 10px; border-bottom: 1px dashed var(--hairline-strong); padding-bottom: 5px;">Kolom 1: Berita Terbaru</h4>
                <div class="form-group">
                    <label for="col1_title">Judul Kolom 1</label>
                    <input type="text" name="tri_column[col1_title]" id="col1_title" class="form-control" value="{{ $triColumn['col1_title'] }}">
                </div>
                <div class="form-group">
                    <div class="switch-container">
                        <label class="switch">
                            <input type="checkbox" name="tri_column[col1_show_news]" value="1" {{ $triColumn['col1_show_news'] ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                        <span style="font-weight: 500;">Muat 3 Berita/Pengumuman Terbaru Secara Otomatis</span>
                    </div>
                </div>

                <h4 style="color: var(--primary); margin-top: 30px; margin-bottom: 10px; border-bottom: 1px dashed var(--hairline-strong); padding-bottom: 5px;">Kolom 2: Agenda & Event</h4>
                <div class="form-group">
                    <label for="col2_title">Judul Kolom 2</label>
                    <input type="text" name="tri_column[col2_title]" id="col2_title" class="form-control" value="{{ $triColumn['col2_title'] }}">
                </div>
                
                <label style="font-weight: 600; display: block; margin-top: 15px; margin-bottom: 5px;">Daftar Agenda (Maksimal 3)</label>
                <div id="eventsContainer">
                    @foreach($triColumn['col2_events'] as $index => $event)
                        <div class="list-builder-item" id="event-row-{{ $index }}">
                            <button type="button" class="btn-remove" onclick="removeListItem('event-row-{{ $index }}')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            <div class="grid-fields-half">
                                <div>
                                    <label>Tgl (Hari)</label>
                                    <input type="text" name="events[{{ $index }}][day]" class="form-control" value="{{ $event['day'] }}" placeholder="15">
                                    <label style="margin-top: 5px;">Bulan</label>
                                    <input type="text" name="events[{{ $index }}][month]" class="form-control" value="{{ $event['month'] }}" placeholder="Jul">
                                </div>
                                <div>
                                    <label>Judul Event</label>
                                    <input type="text" name="events[{{ $index }}][title]" class="form-control" value="{{ $event['title'] }}" placeholder="Nama workshop / event">
                                    <label style="margin-top: 5px;">Waktu & Lokasi</label>
                                    <input type="text" name="events[{{ $index }}][meta]" class="form-control" value="{{ $event['meta'] }}" placeholder="09:00 | Aula LPKIA">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-secondary btn-sm btn-add-item" onclick="addEventRow()">
                    <i class="fa-solid fa-plus"></i> Tambah Agenda
                </button>

                <h4 style="color: var(--primary); margin-top: 30px; margin-bottom: 10px; border-bottom: 1px dashed var(--hairline-strong); padding-bottom: 5px;">Kolom 3: Tentang Program Studi</h4>
                <div class="form-group">
                    <label for="col3_title">Judul Kolom 3</label>
                    <input type="text" name="tri_column[col3_title]" id="col3_title" class="form-control" value="{{ $triColumn['col3_title'] }}">
                </div>
                <div class="form-group">
                    <label for="col3_text">Teks Ringkasan</label>
                    <textarea name="tri_column[col3_text]" id="col3_text" rows="3" class="form-control">{{ $triColumn['col3_text'] }}</textarea>
                </div>
                <div class="grid-2-col" style="gap: 15px;">
                    <div class="form-group">
                        <label for="col3_link_label">Label Link</label>
                        <input type="text" name="tri_column[col3_link_label]" id="col3_link_label" class="form-control" value="{{ $triColumn['col3_link_label'] }}">
                    </div>
                    <div class="form-group">
                        <label for="col3_link_url">URL Link</label>
                        <input type="text" name="tri_column[col3_link_url]" id="col3_link_url" class="form-control" value="{{ $triColumn['col3_link_url'] }}">
                    </div>
                </div>
            </div>

            <!-- PANEL: ABOUT & ORG -->
            <div class="customizer-panel" id="panel-about">
                <div class="customizer-header">
                    <h3>About & Visi Misi</h3>
                    <p>Kelola konten deskripsi detail program studi, Visi Misi, dan Struktur Organisasi.</p>
                </div>

                <div class="form-group">
                    <label for="about_subtitle">Tagline / Subtitle (Di Atas Judul)</label>
                    <input type="text" name="about[about_subtitle]" id="about_subtitle" class="form-control" value="{{ $about['about_subtitle'] }}">
                </div>

                <div class="form-group">
                    <label for="about_title">Judul Utama Profil</label>
                    <input type="text" name="about[about_title]" id="about_title" class="form-control" value="{{ $about['about_title'] }}">
                </div>

                <div class="form-group">
                    <label for="about_text1">Paragraf 1</label>
                    <textarea name="about[about_text1]" id="about_text1" rows="3" class="form-control">{{ $about['about_text1'] }}</textarea>
                </div>

                <div class="form-group">
                    <label for="about_text2">Paragraf 2</label>
                    <textarea name="about[about_text2]" id="about_text2" rows="3" class="form-control">{{ $about['about_text2'] }}</textarea>
                </div>

                <div class="grid-2-col" style="gap: 15px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label for="about_link_label">Label Tombol Profil</label>
                        <input type="text" name="about[about_link_label]" id="about_link_label" class="form-control" value="{{ $about['about_link_label'] }}">
                    </div>
                    <div class="form-group">
                        <label for="about_link_url">URL Tombol Profil</label>
                        <input type="text" name="about[about_link_url]" id="about_link_url" class="form-control" value="{{ $about['about_link_url'] }}">
                    </div>
                </div>

                <h4 style="color: var(--primary); margin-top: 30px; margin-bottom: 10px; border-bottom: 1px dashed var(--hairline-strong); padding-bottom: 5px;">Visi & Misi Box</h4>
                <div class="form-group">
                    <label for="visi_title">Judul Visi</label>
                    <input type="text" name="about[visi_title]" id="visi_title" class="form-control" value="{{ $about['visi_title'] }}">
                </div>
                <div class="form-group">
                    <label for="visi_text">Teks Visi (Gunakan tanda kutip jika perlu)</label>
                    <textarea name="about[visi_text]" id="visi_text" rows="3" class="form-control">{{ $about['visi_text'] }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="misi_title">Judul Misi</label>
                    <input type="text" name="about[misi_title]" id="misi_title" class="form-control" value="{{ $about['misi_title'] }}">
                </div>

                <label style="font-weight: 600; display: block; margin-top: 15px; margin-bottom: 5px;">Butir Misi (List Items)</label>
                <div id="misiContainer">
                    @foreach($about['misi_items'] as $index => $misiItem)
                        <div class="list-builder-item" id="misi-row-{{ $index }}" style="padding: 10px 15px;">
                            <button type="button" class="btn-remove" onclick="removeListItem('misi-row-{{ $index }}')" style="top: 10px;">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                            <div style="padding-right: 30px;">
                                <input type="text" name="misi[{{ $index }}]" class="form-control" value="{{ $misiItem }}" placeholder="Butir Misi Program Studi">
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-secondary btn-sm btn-add-item" onclick="addMisiRow()">
                    <i class="fa-solid fa-plus"></i> Tambah Misi
                </button>

                <h4 style="color: var(--primary); margin-top: 30px; margin-bottom: 10px; border-bottom: 1px dashed var(--hairline-strong); padding-bottom: 5px;">Struktur Organisasi</h4>
                <div class="form-group">
                    <div class="switch-container">
                        <label class="switch">
                            <input type="checkbox" name="about[show_org]" value="1" {{ $about['show_org'] ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                        <span style="font-weight: 500;">Tampilkan Anggota Struktur Organisasi di Bagian Bawah Halaman</span>
                    </div>
                </div>
            </div>

            <!-- PANEL: DOWNLOADS & ANNOUNCEMENT -->
            <div class="customizer-panel" id="panel-downloads">
                <div class="customizer-header">
                    <h3>Unduhan & Pengumuman</h3>
                    <p>Atur berkas-berkas yang dapat diunduh siswa serta pengumuman penting.</p>
                </div>

                <div class="form-group">
                    <label for="download_title">Judul Section Unduhan</label>
                    <input type="text" name="downloads[download_title]" id="download_title" class="form-control" value="{{ $downloads['download_title'] }}">
                </div>

                <div class="form-group">
                    <label filter="download_subtitle">Keterangan / Deskripsi</label>
                    <textarea name="downloads[download_subtitle]" id="download_subtitle" rows="2" class="form-control">{{ $downloads['download_subtitle'] }}</textarea>
                </div>

                <label style="font-weight: 600; display: block; margin-top: 15px; margin-bottom: 5px;">Daftar File Unduhan</label>
                <div id="downloadsContainer">
                    @foreach($downloads['downloads'] as $index => $dl)
                        <div class="list-builder-item" id="dl-row-{{ $index }}">
                            <button type="button" class="btn-remove" onclick="removeListItem('dl-row-{{ $index }}')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            <div class="grid-fields">
                                <div>
                                    <label>Nama File</label>
                                    <input type="text" name="dls[{{ $index }}][name]" class="form-control" value="{{ $dl['name'] }}" placeholder="Kurikulum AB 2026.pdf">
                                </div>
                                <div>
                                    <label>Ukuran / Jenis Dokumen</label>
                                    <input type="text" name="dls[{{ $index }}][meta]" class="form-control" value="{{ $dl['meta'] }}" placeholder="Ukuran: 1.4 MB | PDF">
                                </div>
                                <div>
                                    <label>Tautan URL</label>
                                    <input type="text" name="dls[{{ $index }}][url]" class="form-control" value="{{ $dl['url'] }}" placeholder="# atau link download">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-secondary btn-sm btn-add-item" onclick="addDownloadRow()">
                    <i class="fa-solid fa-plus"></i> Tambah File
                </button>

                <h4 style="color: var(--primary); margin-top: 30px; margin-bottom: 10px; border-bottom: 1px dashed var(--hairline-strong); padding-bottom: 5px;">Pengumuman Terkini (Box Kanan)</h4>
                
                <div class="form-group">
                    <label for="announcement_badge">Badge Pengumuman</label>
                    <input type="text" name="downloads[announcement_badge]" id="announcement_badge" class="form-control" value="{{ $downloads['announcement_badge'] }}">
                </div>

                <div class="form-group">
                    <label for="announcement_title">Judul Pengumuman</label>
                    <input type="text" name="downloads[announcement_title]" id="announcement_title" class="form-control" value="{{ $downloads['announcement_title'] }}">
                </div>

                <div class="form-group">
                    <label for="announcement_text">Isi Ringkas Pengumuman</label>
                    <textarea name="downloads[announcement_text]" id="announcement_text" rows="3" class="form-control">{{ $downloads['announcement_text'] }}</textarea>
                </div>

                <div class="grid-2-col" style="gap: 15px;">
                    <div class="form-group">
                        <label for="announcement_btn_label">Label Link</label>
                        <input type="text" name="downloads[announcement_btn_label]" id="announcement_btn_label" class="form-control" value="{{ $downloads['announcement_btn_label'] }}">
                    </div>
                    <div class="form-group">
                        <label for="announcement_btn_url">URL Link</label>
                        <input type="text" name="downloads[announcement_btn_url]" id="announcement_btn_url" class="form-control" value="{{ $downloads['announcement_btn_url'] }}">
                    </div>
                </div>
            </div>

            <!-- PANEL: THEME (COLORS & FONTS) -->
            <div class="customizer-panel" id="panel-theme">
                <div class="customizer-header">
                    <h3>Tema Warna & Font</h3>
                    <p>Sesuaikan skema warna utama (primary), warna brand/teal, dan tipografi situs.</p>
                </div>

                <div class="grid-2-col" style="gap: 15px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label for="theme_primary_color">Warna Utama (Primary)</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="color" id="theme_primary_color_picker" class="form-control" style="width: 60px; padding: 5px; height: 38px; cursor: pointer;" value="{{ $theme['theme_primary_color'] }}" oninput="document.getElementById('theme_primary_color').value = this.value">
                            <input type="text" name="theme_primary_color" id="theme_primary_color" class="form-control" value="{{ $theme['theme_primary_color'] }}" placeholder="#f25c3f" maxlength="7" oninput="document.getElementById('theme_primary_color_picker').value = this.value">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="theme_primary_deep_color">Warna Utama Gelap (Primary Deep)</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="color" id="theme_primary_deep_color_picker" class="form-control" style="width: 60px; padding: 5px; height: 38px; cursor: pointer;" value="{{ $theme['theme_primary_deep_color'] }}" oninput="document.getElementById('theme_primary_deep_color').value = this.value">
                            <input type="text" name="theme_primary_deep_color" id="theme_primary_deep_color" class="form-control" value="{{ $theme['theme_primary_deep_color'] }}" placeholder="#e14a2c" maxlength="7" oninput="document.getElementById('theme_primary_deep_color_picker').value = this.value">
                        </div>
                    </div>
                </div>

                <div class="grid-3-col" style="gap: 15px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label for="theme_brand_teal_deep_color">Warna Utility Bar (Teal Deep)</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="color" id="theme_brand_teal_deep_color_picker" class="form-control" style="width: 50px; padding: 3px; height: 38px; cursor: pointer;" value="{{ $theme['theme_brand_teal_deep_color'] }}" oninput="document.getElementById('theme_brand_teal_deep_color').value = this.value">
                            <input type="text" name="theme_brand_teal_deep_color" id="theme_brand_teal_deep_color" class="form-control" value="{{ $theme['theme_brand_teal_deep_color'] }}" placeholder="#0c2340" maxlength="7" oninput="document.getElementById('theme_brand_teal_deep_color_picker').value = this.value">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="theme_brand_teal_color">Warna Header Brand (Teal)</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="color" id="theme_brand_teal_color_picker" class="form-control" style="width: 50px; padding: 3px; height: 38px; cursor: pointer;" value="{{ $theme['theme_brand_teal_color'] }}" oninput="document.getElementById('theme_brand_teal_color').value = this.value">
                            <input type="text" name="theme_brand_teal_color" id="theme_brand_teal_color" class="form-control" value="{{ $theme['theme_brand_teal_color'] }}" placeholder="#0f2d52" maxlength="7" oninput="document.getElementById('theme_brand_teal_color_picker').value = this.value">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="theme_brand_teal_mid_color">Warna Menengah Brand (Teal Mid)</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="color" id="theme_brand_teal_mid_color_picker" class="form-control" style="width: 50px; padding: 3px; height: 38px; cursor: pointer;" value="{{ $theme['theme_brand_teal_mid_color'] }}" oninput="document.getElementById('theme_brand_teal_mid_color').value = this.value">
                            <input type="text" name="theme_brand_teal_mid_color" id="theme_brand_teal_mid_color" class="form-control" value="{{ $theme['theme_brand_teal_mid_color'] }}" placeholder="#164275" maxlength="7" oninput="document.getElementById('theme_brand_teal_mid_color_picker').value = this.value">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="theme_font_family">Pilihan Font Keluarga (Typography)</label>
                    <select name="theme_font_family" id="theme_font_family" class="form-control">
                        <option value="Plus Jakarta Sans" {{ $theme['theme_font_family'] === 'Plus Jakarta Sans' ? 'selected' : '' }}>Plus Jakarta Sans (Default Modern Geometric)</option>
                        <option value="Inter" {{ $theme['theme_font_family'] === 'Inter' ? 'selected' : '' }}>Inter (Sleek Tech & Admin UI)</option>
                        <option value="Roboto" {{ $theme['theme_font_family'] === 'Roboto' ? 'selected' : '' }}>Roboto (Universal Neomorphic)</option>
                        <option value="Outfit" {{ $theme['theme_font_family'] === 'Outfit' ? 'selected' : '' }}>Outfit (Elegant Geometric & Minimalist)</option>
                        <option value="Playfair Display" {{ $theme['theme_font_family'] === 'Playfair Display' ? 'selected' : '' }}>Playfair Display (Premium Editorial Serif)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="theme_custom_css">Kustom CSS (Advanced)</label>
                    <textarea name="theme_custom_css" id="theme_custom_css" rows="6" class="form-control" style="font-family: var(--font-mono); font-size: 13px;" placeholder="/* Tambahkan kustom gaya CSS di sini */">{{ $theme['theme_custom_css'] }}</textarea>
                    <small class="form-text text-muted">Gaya CSS ini akan disuntikkan secara global di seluruh halaman utama dan dinamis prodi.</small>
                </div>
            </div>

            <!-- PANEL: SECTIONS ORDER -->
            <div class="customizer-panel" id="panel-order">
                <div class="customizer-header">
                    <h3>Urutan & Visibilitas Section Beranda</h3>
                    <p>Centang untuk menampilkan section, dan gunakan tombol panah untuk mengatur susunan urutan section dari atas ke bawah.</p>
                </div>

                <div class="section-order-list" id="sectionOrderList">
                    <!-- Loaded dynamically via JavaScript using the order list -->
                </div>
            </div>
        </main>
    </div>
</form>
@endsection

@section('scripts')
<script>
    // Tab switching
    function switchTab(tabName, button) {
        document.querySelectorAll('.customizer-tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.customizer-panel').forEach(panel => panel.classList.remove('active'));
        
        button.classList.add('active');
        document.getElementById(`panel-${tabName}`).classList.add('active');
    }

    // Toggle static page select visibility
    function toggleHomepageType(val) {
        const group = document.getElementById('staticPageSelectGroup');
        if (val === 'static_page') {
            group.style.display = 'block';
        } else {
            group.style.display = 'none';
        }
    }

    // List Item builder row counts
    let eventCount = {{ count($triColumn['col2_events']) }};
    let misiCount = {{ count($about['misi_items']) }};
    let dlCount = {{ count($downloads['downloads']) }};

    function removeListItem(id) {
        if (confirm('Hapus item ini?')) {
            const item = document.getElementById(id);
            if (item) item.remove();
        }
    }

    function addEventRow() {
        if (eventCount >= 3) {
            alert('Maksimal agenda event adalah 3.');
            return;
        }
        const container = document.getElementById('eventsContainer');
        const rowId = `event-row-${eventCount}`;
        const html = `
            <div class="list-builder-item" id="${rowId}">
                <button type="button" class="btn-remove" onclick="removeListItem('${rowId}')">
                    <i class="fa-solid fa-trash"></i>
                </button>
                <div class="grid-fields-half">
                    <div>
                        <label>Tgl (Hari)</label>
                        <input type="text" name="events[${eventCount}][day]" class="form-control" placeholder="15">
                        <label style="margin-top: 5px;">Bulan</label>
                        <input type="text" name="events[${eventCount}][month]" class="form-control" placeholder="Jul">
                    </div>
                    <div>
                        <label>Judul Event</label>
                        <input type="text" name="events[${eventCount}][title]" class="form-control" placeholder="Nama workshop / event">
                        <label style="margin-top: 5px;">Waktu & Lokasi</label>
                        <input type="text" name="events[${eventCount}][meta]" class="form-control" placeholder="09:00 | Aula LPKIA">
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        eventCount++;
    }

    function addMisiRow() {
        const container = document.getElementById('misiContainer');
        const rowId = `misi-row-${misiCount}`;
        const html = `
            <div class="list-builder-item" id="${rowId}" style="padding: 10px 15px;">
                <button type="button" class="btn-remove" onclick="removeListItem('${rowId}')" style="top: 10px;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <div style="padding-right: 30px;">
                    <input type="text" name="misi[${misiCount}]" class="form-control" placeholder="Butir Misi Program Studi">
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        misiCount++;
    }

    function addDownloadRow() {
        const container = document.getElementById('downloadsContainer');
        const rowId = `dl-row-${dlCount}`;
        const html = `
            <div class="list-builder-item" id="${rowId}">
                <button type="button" class="btn-remove" onclick="removeListItem('${rowId}')">
                    <i class="fa-solid fa-trash"></i>
                </button>
                <div class="grid-fields">
                    <div>
                        <label>Nama File</label>
                        <input type="text" name="dls[${dlCount}][name]" class="form-control" placeholder="Kurikulum AB 2026.pdf">
                    </div>
                    <div>
                        <label>Ukuran / Jenis Dokumen</label>
                        <input type="text" name="dls[${dlCount}][meta]" class="form-control" placeholder="Ukuran: 1.4 MB | PDF">
                    </div>
                    <div>
                        <label>Tautan URL</label>
                        <input type="text" name="dls[${dlCount}][url]" class="form-control" placeholder="# atau link download">
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        dlCount++;
    }

    // Section Ordering and Visibility Logic
    const sectionNames = {
        'hero': 'Hero Banner & Inquiry Form',
        'stats': 'Stats Counter Banner',
        'tri_column': 'Tri-Column Featured Widgets (News, Event, About)',
        'about': 'About Description & Visi Misi / Organisasi',
        'lecturers': 'Dosen Ahli & Pengajar Grid',
        'downloads': 'Downloads Area & Pengumuman Penting',
        'news': 'Berita & Kegiatan Terbaru Grid'
    };

    let currentOrder = @json($sectionsOrder);
    let currentVisibility = @json($sectionsVisibility);

    function renderOrderPanel() {
        const container = document.getElementById('sectionOrderList');
        container.innerHTML = '';
        
        currentOrder.forEach((sec, idx) => {
            const isVisible = currentVisibility[sec] !== undefined ? currentVisibility[sec] : true;
            const label = sectionNames[sec] || sec;
            
            const isFirst = idx === 0;
            const isLast = idx === currentOrder.length - 1;

            const html = `
                <div class="section-order-item" data-id="${sec}">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div class="switch-container">
                            <label class="switch">
                                <input type="checkbox" class="sec-vis-check" data-sec="${sec}" ${isVisible ? 'checked' : ''} onchange="toggleSecVis('${sec}', this.checked)">
                                <span class="slider"></span>
                            </label>
                        </div>
                        <span>${label}</span>
                    </div>
                    
                    <div class="actions">
                        <button type="button" class="btn-move" onclick="moveSection(${idx}, -1)" ${isFirst ? 'disabled' : ''}>
                            <i class="fa-solid fa-chevron-up"></i>
                        </button>
                        <button type="button" class="btn-move" onclick="moveSection(${idx}, 1)" ${isLast ? 'disabled' : ''}>
                            <i class="fa-solid fa-chevron-down"></i>
                        </button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        });

        // Sync to hidden inputs
        document.getElementById('sectionsOrderInput').value = JSON.stringify(currentOrder);
        document.getElementById('sectionsVisibilityInput').value = JSON.stringify(currentVisibility);
    }

    function toggleSecVis(sec, val) {
        currentVisibility[sec] = val;
        document.getElementById('sectionsVisibilityInput').value = JSON.stringify(currentVisibility);
    }

    function moveSection(idx, dir) {
        const targetIdx = idx + dir;
        if (targetIdx < 0 || targetIdx >= currentOrder.length) return;
        
        // Swap elements
        const temp = currentOrder[idx];
        currentOrder[idx] = currentOrder[targetIdx];
        currentOrder[targetIdx] = temp;

        renderOrderPanel();
    }

    // Initialize Order list
    document.addEventListener('DOMContentLoaded', () => {
        renderOrderPanel();
    });
</script>
@endsection
