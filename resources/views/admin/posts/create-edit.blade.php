@extends('layouts.admin')

@section('title', isset($post) ? 'Edit Postingan - Admin LPKIA' : 'Tambah Postingan Baru - Admin LPKIA')
@section('page_title', isset($post) ? 'Edit Postingan' : 'Buat Postingan Baru')

@section('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    /* Styling overrides for Editor */
    .form-group.title-group {
        margin-bottom: 25px;
    }
    .form-control.title-input {
        font-size: 1.6rem;
        font-weight: 800;
        border: none;
        border-bottom: 2px solid var(--border-color);
        border-radius: 0;
        padding: 10px 0;
        color: var(--primary);
    }
    .form-control.title-input:focus {
        border-bottom-color: var(--secondary);
        box-shadow: none;
    }
</style>
@endsection

@section('content')

    <!-- Auto-save notification feedback indicator -->
    <div id="autosaveIndicator" style="display: none; font-size: 0.8rem; color: var(--success); font-weight: 600; margin-bottom: 15px; text-align: right;">
        <i class="fa-solid fa-spinner fa-spin"></i> Menyimpan draf secara otomatis...
    </div>

    <!-- Main Form -->
    <form action="{{ isset($post) ? route('admin.posts.update', $post->id) : route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" id="postEditorForm">
        @csrf
        @if(isset($post))
            @method('PUT')
        @endif
        
        <!-- Post ID for AJAX autosave -->
        <input type="hidden" name="post_id" id="postIdInput" value="{{ $post->id ?? '' }}">

        <div class="editor-layout">
            <!-- Left Panel: Main Workspace Area -->
            <div class="editor-main-area">
                <div class="form-group title-group">
                    <label class="form-label" style="font-size: 1rem; color: var(--primary);">Judul Konten</label>
                    <input type="text" name="title" id="postTitle" class="form-control title-input" placeholder="Masukkan Judul Postingan Menarik di Sini..." required value="{{ old('title', $post->title ?? '') }}">
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 10px;">
                        <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0;">Konten Postingan</label>
                        <div style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap;">
                            <span style="font-size: 0.8rem; font-weight: 700; color: var(--stone); margin-right: 5px;">⚡ Sisipkan Widget:</span>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="openWidgetModal('gallery')" style="padding: 4px 8px; font-size: 11px;">
                                <i class="fa-solid fa-images"></i> Galeri Foto
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="openWidgetModal('youtube')" style="padding: 4px 8px; font-size: 11px;">
                                <i class="fa-solid fa-video"></i> Video YouTube
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="openWidgetModal('callout')" style="padding: 4px 8px; font-size: 11px;">
                                <i class="fa-solid fa-circle-info"></i> Callout Info
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="openWidgetModal('button')" style="padding: 4px 8px; font-size: 11px;">
                                <i class="fa-solid fa-square-caret-right"></i> Tombol Link
                            </button>
                        </div>
                    </div>
                    <!-- Quill Container -->
                    <div id="quillEditor" style="height: 350px;">
                        {!! old('content', $post->content ?? '') !!}
                    </div>
                    <!-- Hidden Input to store HTML -->
                    <input type="hidden" name="content" id="postContentInput">
                </div>
            </div>

            <!-- Right Panel: Sidebar Properties Area -->
            <div class="editor-sidebar-area">
                
                <!-- Status & Publish Actions -->
                <div class="editor-sidebar-widget">
                    <div class="editor-widget-title">Penerbitan (Publish)</div>
                    
                    <div class="form-group">
                        <label class="form-label">Status Postingan</label>
                        <select name="status" id="postStatus" class="form-control" required onchange="toggleScheduledDate()">
                            <option value="draft" {{ (old('status', $post->status ?? '') === 'draft') ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ (old('status', $post->status ?? '') === 'published') ? 'selected' : '' }}>Published</option>
                            <option value="scheduled" {{ (old('status', $post->status ?? '') === 'scheduled') ? 'selected' : '' }}>Scheduled</option>
                        </select>
                    </div>

                    <!-- Scheduled Date -->
                    <div class="form-group" id="scheduledDateGroup" style="display: {{ (old('status', $post->status ?? '') === 'scheduled') ? 'block' : 'none' }};">
                        <label class="form-label">Tanggal Terbit Terjadwal</label>
                        <input type="datetime-local" name="published_at" id="publishedAt" class="form-control" value="{{ isset($post) && $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '' }}">
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 20px;">
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fa-solid fa-cloud-arrow-up"></i> Simpan Konten
                        </button>
                        <button type="button" class="btn btn-outline btn-sm" id="btnLivePreview" style="width: 100%;">
                            <i class="fa-solid fa-eye"></i> Live Preview
                        </button>
                    </div>
                </div>

                <!-- Category selection -->
                <div class="editor-sidebar-widget">
                    <div class="editor-widget-title">Kategori</div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Pilih Kategori Postingan</label>
                        <select name="category_id" id="postCategoryId" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ (old('category_id', $post->category_id ?? '') == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Featured Image Upload -->
                <div class="editor-sidebar-widget">
                    <div class="editor-widget-title">Gambar Utama (Featured Image)</div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <!-- Custom Drag & Drop Box -->
                        <div class="image-upload-box" id="imageDropZone" onclick="triggerFileInput()">
                            <i class="fa-solid fa-cloud-arrow-up image-upload-icon"></i>
                            <div class="image-upload-text">Klik atau seret file gambar ke sini (JPG, PNG, max 2MB)</div>
                            <input type="file" name="featured_image" id="featuredImageFile" style="display: none;" accept="image/*" onchange="previewImage(this)">
                            
                            @if(isset($post) && $post->featured_image)
                                <img src="{{ asset($post->featured_image) }}" class="image-upload-preview" id="imagePreviewElement">
                            @else
                                <img class="image-upload-preview" id="imagePreviewElement" style="display: none;">
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <!-- Side-out Live Preview Drawer Panel -->
    <div class="preview-backdrop" id="previewBackdrop"></div>
    <div class="preview-modal" id="previewDrawer">
        <div class="preview-header">
            <h3><i class="fa-solid fa-magnifying-glass-chart"></i> Live Preview Mode</h3>
            <button type="button" class="btn btn-sm btn-danger btn-icon" id="btnClosePreview">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="preview-body">
            <h2 id="previewTitle" style="font-size: 2.2rem; font-weight: 800; color: var(--primary); margin-bottom: 20px; line-height: 1.3;">Judul Postingan</h2>
            <div id="previewMetadata" style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 30px; border-bottom: 1px solid var(--border-color); padding-bottom: 15px;">
                <span><i class="fa-solid fa-folder"></i> <span id="previewCategoryText">Akademik</span></span> &nbsp;|&nbsp; 
                <span><i class="fa-solid fa-user-pen"></i> Oleh: Anda</span> &nbsp;|&nbsp; 
                <span><i class="fa-solid fa-clock"></i> Baru saja</span>
            </div>
            
            <div id="previewImageContainer" style="display: none; margin-bottom: 30px;">
                <img id="previewFeaturedImg" src="" style="width: 100%; max-height: 280px; object-fit: cover; border-radius: 8px;">
            </div>

            <div class="page-body-content" id="previewContentBody">
                <!-- Quill HTML rendered here -->
            </div>
        </div>
    </div>

    <!-- Modal Inserter Widget -->
    <div id="widgetModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
        <div style="background-color:#fff; border-radius:8px; width:100%; max-width:550px; box-shadow:0 4px 20px rgba(0,0,0,0.25); display:flex; flex-direction:column; overflow:hidden;">
            <!-- Modal Header -->
            <div style="padding:15px 20px; background-color:var(--brand-teal-deep); color:#fff; display:flex; justify-content:space-between; align-items:center;">
                <h4 style="margin:0; font-size:1.1rem; color:#fff;" id="widgetModalTitle">Sisipkan Widget</h4>
                <button type="button" onclick="closeWidgetModal()" style="background:none; border:none; color:#fff; font-size:1.2rem; cursor:pointer;"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <!-- Modal Body (Dynamic Forms) -->
            <div style="padding:20px; max-height:400px; overflow-y:auto;" id="widgetModalBody">
                
                <!-- GALLERY FORM -->
                <div id="widgetForm-gallery" class="widget-form-section" style="display:none;">
                    <!-- Drag & Drop Uploader Area -->
                    <div class="form-group" style="margin-bottom:15px;">
                        <label class="form-label" style="font-weight:600;">Unggah File Gambar</label>
                        <div id="galleryUploadZone" style="border:2px dashed #ccc; border-radius:6px; padding:20px; text-align:center; cursor:pointer; background-color:#fafafa; transition: border-color 0.2s;" onclick="triggerGalleryFileInput()">
                            <i class="fa-solid fa-cloud-arrow-up" style="font-size:1.8rem; color:#aaa; margin-bottom:8px;"></i>
                            <div style="font-size:0.85rem; color:#666;">Klik atau seret file gambar ke sini untuk diunggah</div>
                            <input type="file" id="galleryUploadFiles" style="display:none;" accept="image/*" multiple onchange="uploadGalleryImages(this)">
                        </div>
                        <div id="galleryUploadStatus" style="font-size:0.8rem; font-weight:600; color:var(--success); margin-top:8px; display:none;">
                            <i class="fa-solid fa-spinner fa-spin"></i> Sedang mengunggah...
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" style="font-weight:600;">Daftar URL Gambar (Satu URL per baris)</label>
                        <textarea id="widgetGalleryUrls" class="form-control" rows="4" placeholder="https://example.com/image1.jpg&#10;https://example.com/image2.jpg" style="font-family:monospace; font-size:11px;"></textarea>
                    </div>
                    <div class="grid-2-col" style="gap:15px; margin-top:15px;">
                        <div class="form-group">
                            <label class="form-label" style="font-weight:600;">Jumlah Kolom Grid</label>
                            <select id="widgetGalleryCols" class="form-control">
                                <option value="2">2 Kolom</option>
                                <option value="3" selected>3 Kolom (Rekomendasi)</option>
                                <option value="4">4 Kolom</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-weight:600;">Grid Gap (Jarak)</label>
                            <select id="widgetGalleryGap" class="form-control">
                                <option value="10">Rapat (10px)</option>
                                <option value="15" selected>Sedang (15px)</option>
                                <option value="25">Lebar (25px)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- YOUTUBE FORM -->
                <div id="widgetForm-youtube" class="widget-form-section" style="display:none;">
                    <div class="form-group">
                        <label class="form-label" style="font-weight:600;">Tautan URL Video YouTube</label>
                        <input type="text" id="widgetYoutubeUrl" class="form-control" placeholder="https://www.youtube.com/watch?v=dQw4w9WgXcQ atau embed code">
                        <small class="form-text text-muted" style="margin-top:5px; display:block;">Mendukung tautan biasa, tautan pendek (youtu.be), maupun kode iframe embed.</small>
                    </div>
                </div>

                <!-- CALLOUT FORM -->
                <div id="widgetForm-callout" class="widget-form-section" style="display:none;">
                    <div class="form-group">
                        <label class="form-label" style="font-weight:600;">Jenis Callout (Gaya Box)</label>
                        <select id="widgetCalloutType" class="form-control">
                            <option value="info">Biru - Informasi Penting</option>
                            <option value="success">Hijau - Keterangan Sukses / Tips</option>
                            <option value="warning">Oranye - Peringatan / Catatan</option>
                            <option value="danger">Merah - Penting / Larangan</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-top:15px;">
                        <label class="form-label" style="font-weight:600;">Teks Catatan / Pengumuman</label>
                        <textarea id="widgetCalloutText" class="form-control" rows="4" placeholder="Ketik pesan informasi di sini..."></textarea>
                    </div>
                </div>

                <!-- BUTTON FORM -->
                <div id="widgetForm-button" class="widget-form-section" style="display:none;">
                    <div class="grid-2-col" style="gap:15px;">
                        <div class="form-group">
                            <label class="form-label" style="font-weight:600;">Teks Tombol</label>
                            <input type="text" id="widgetBtnLabel" class="form-control" placeholder="Hubungi Kami">
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-weight:600;">Warna Tombol</label>
                            <select id="widgetBtnStyle" class="form-control">
                                <option value="primary">Warna Utama (Primary)</option>
                                <option value="secondary">Teal Muda (Secondary)</option>
                                <option value="brand">Teal Gelap (Brand)</option>
                                <option value="success">Hijau (Success)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:15px;">
                        <label class="form-label" style="font-weight:600;">Alamat Link URL Tujuan</label>
                        <input type="text" id="widgetBtnUrl" class="form-control" placeholder="https://wa.me/6281...">
                    </div>
                    <div class="form-group" style="margin-top:15px;">
                        <label class="form-label" style="font-weight:600;">Perataan (Alignment)</label>
                        <select id="widgetBtnAlign" class="form-control">
                            <option value="left">Rata Kiri</option>
                            <option value="center" selected>Rata Tengah</option>
                            <option value="right">Rata Kanan</option>
                        </select>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div style="padding:15px 20px; background-color:#f9f9f9; border-top:1px solid #eee; display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeWidgetModal()" class="btn btn-outline btn-sm">Batal</button>
                <button type="button" onclick="insertWidgetToEditor()" class="btn btn-primary btn-sm">Sisipkan Widget</button>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    // Initialize Quill Editor
    const quill = new Quill('#quillEditor', {
        theme: 'snow',
        placeholder: 'Mulai ketik isi postingan berita LPKIA di sini...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'link'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['clean']
            ]
        }
    });

    // Handle form submit to map quill editor output to hidden input field
    const form = document.getElementById('postEditorForm');
    const contentInput = document.getElementById('postContentInput');

    form.addEventListener('submit', function() {
        contentInput.value = quill.root.innerHTML;
    });

    // Toggle Scheduled date display
    function toggleScheduledDate() {
        const status = document.getElementById('postStatus').value;
        const group = document.getElementById('scheduledDateGroup');
        if (status === 'scheduled') {
            group.style.display = 'block';
        } else {
            group.style.display = 'none';
        }
    }

    // Trigger Hidden Input File Click
    function triggerFileInput() {
        document.getElementById('featuredImageFile').click();
    }

    // Preview Uploaded Image
    function previewImage(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('imagePreviewElement');
                preview.src = e.target.result;
                preview.style.display = 'block';
                
                // Show in live preview image as well
                const livePreviewImg = document.getElementById('previewFeaturedImg');
                livePreviewImg.src = e.target.result;
                document.getElementById('previewImageContainer').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    }

    // Setup Drag and Drop events
    const dropZone = document.getElementById('imageDropZone');
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
        }, false);
    });

    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        const fileInput = document.getElementById('featuredImageFile');
        
        fileInput.files = files;
        previewImage(fileInput);
        showToast('Gambar berhasil ditempel ke zona unggah.', 'success');
    });

    // Live Preview Drawer logic
    const btnPreview = document.getElementById('btnLivePreview');
    const btnClosePreview = document.getElementById('btnClosePreview');
    const previewDrawer = document.getElementById('previewDrawer');
    const backdrop = document.getElementById('previewBackdrop');

    btnPreview.addEventListener('click', () => {
        // Populate preview content
        document.getElementById('previewTitle').innerText = document.getElementById('postTitle').value || 'Judul Postingan Anda';
        
        const catSelect = document.getElementById('postCategoryId');
        const selectedCatText = catSelect.options[catSelect.selectedIndex]?.text || 'Kategori';
        document.getElementById('previewCategoryText').innerText = selectedCatText;

        document.getElementById('previewContentBody').innerHTML = quill.root.innerHTML || '<p style="color:var(--text-light)">Konten kosong.</p>';
        
        // Show drawer
        previewDrawer.classList.add('active');
        backdrop.classList.add('active');
    });

    function closePreview() {
        previewDrawer.classList.remove('active');
        backdrop.classList.remove('active');
    }

    btnClosePreview.addEventListener('click', closePreview);
    backdrop.addEventListener('click', closePreview);


    // --- PERIODICAL AUTO-SAVE UX IMPLEMENTATION ---
    let autoSaveTimer = setInterval(performAutoSave, 30000); // Trigger auto-save every 30 seconds

    function performAutoSave() {
        const title = document.getElementById('postTitle').value.trim();
        const catId = document.getElementById('postCategoryId').value;
        const status = document.getElementById('postStatus').value;
        const content = quill.root.innerHTML;
        const postId = document.getElementById('postIdInput').value;

        // Skip if title or category is missing (not ready for draf creation)
        if (!title || !catId) {
            return;
        }

        const indicator = document.getElementById('autosaveIndicator');
        indicator.style.display = 'block';

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Fetch AJAX Request
        fetch('{{ route("admin.posts.autosave") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                id: postId,
                title: title,
                category_id: catId,
                status: status,
                content: content,
                published_at: document.getElementById('publishedAt').value
            })
        })
        .then(response => response.json())
        .then(data => {
            indicator.style.display = 'none';
            if (data.success) {
                // Update Post ID on hidden input
                document.getElementById('postIdInput').value = data.id;
                showToast(data.message, 'success');
            }
        })
        .catch(err => {
            indicator.style.display = 'none';
            console.error('Autosave Error:', err);
        });
    }

    // --- WIDGET INSERTER LOGIC ---
    let activeWidgetType = '';

    window.openWidgetModal = function(type) {
        activeWidgetType = type;
        
        // Hide all forms first
        document.querySelectorAll('.widget-form-section').forEach(el => el.style.display = 'none');
        
        // Setup titles
        const titles = {
            'gallery': 'Sisipkan Galeri Gambar Grid',
            'youtube': 'Sisipkan Video YouTube Responsif',
            'callout': 'Sisipkan Kotak Info (Callout)',
            'button': 'Sisipkan Tombol Aksi (CTA Link)'
        };
        document.getElementById('widgetModalTitle').innerText = titles[type] || 'Sisipkan Widget';
        
        // Show specific form
        document.getElementById(`widgetForm-${type}`).style.display = 'block';
        
        // Show modal
        const modal = document.getElementById('widgetModal');
        modal.style.display = 'flex';
    };

    window.closeWidgetModal = function() {
        document.getElementById('widgetModal').style.display = 'none';
    };

    function parseYoutubeId(url) {
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
        const match = url.match(regExp);
        return (match && match[2].length === 11) ? match[2] : null;
    }

    window.insertWidgetToEditor = function() {
        let html = '';
        
        if (activeWidgetType === 'gallery') {
            const urlsText = document.getElementById('widgetGalleryUrls').value.trim();
            const cols = document.getElementById('widgetGalleryCols').value;
            const gap = document.getElementById('widgetGalleryGap').value;
            
            if (!urlsText) {
                alert('Silakan masukkan minimal satu URL gambar.');
                return;
            }
            
            const urls = urlsText.split('\n').map(u => u.trim()).filter(u => u.length > 0);
            
            html = `<div class="content-gallery-grid cols-${cols}" style="display: grid; grid-template-columns: repeat(${cols}, 1fr); gap: ${gap}px; margin: 25px 0;">`;
            urls.forEach(url => {
                html += `<div class="gallery-item"><img src="${url}" style="width: 100%; border-radius: 8px; object-fit: cover; aspect-ratio: 4/3;"></div>`;
            });
            html += `</div><p><br></p>`;
            
            // Clean form
            document.getElementById('widgetGalleryUrls').value = '';
            
        } else if (activeWidgetType === 'youtube') {
            const input = document.getElementById('widgetYoutubeUrl').value.trim();
            if (!input) {
                alert('Silakan masukkan link video YouTube.');
                return;
            }
            
            let videoId = parseYoutubeId(input);
            if (!videoId && input.includes('iframe')) {
                // Try parsing from iframe src
                const srcMatch = input.match(/src=["']([^"']+)["']/);
                if (srcMatch) {
                    videoId = parseYoutubeId(srcMatch[1]);
                }
            }
            
            if (!videoId) {
                alert('Tautan YouTube tidak valid. Harap periksa kembali.');
                return;
            }
            
            html = `
                <div class="responsive-video-container" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%; margin: 25px 0; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <iframe src="https://www.youtube.com/embed/${videoId}" frameborder="0" allowfullscreen style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></iframe>
                </div>
                <p><br></p>
            `;
            
            // Clean form
            document.getElementById('widgetYoutubeUrl').value = '';
            
        } else if (activeWidgetType === 'callout') {
            const type = document.getElementById('widgetCalloutType').value;
            const text = document.getElementById('widgetCalloutText').value.trim();
            
            if (!text) {
                alert('Silakan isi teks catatan.');
                return;
            }
            
            const borderColors = {
                'info': '#3b82f6',
                'success': '#10b981',
                'warning': '#f59e0b',
                'danger': '#ef4444'
            };
            const bgColors = {
                'info': '#eff6ff',
                'success': '#ecfdf5',
                'warning': '#fffbeb',
                'danger': '#fef2f2'
            };
            const textColors = {
                'info': '#1e3a8a',
                'success': '#065f46',
                'warning': '#92400e',
                'danger': '#991b1b'
            };
            const labels = {
                'info': 'INFORMASI',
                'success': 'TIPS / CATATAN',
                'warning': 'PERINGATAN',
                'danger': 'PENTING / PERHATIAN'
            };
            
            html = `
                <div class="callout callout-${type}" style="padding: 15px 20px; margin: 25px 0; border-left: 5px solid ${borderColors[type]}; background-color: ${bgColors[type]}; border-radius: 4px; color: ${textColors[type]}; font-size: 0.95rem; line-height: 1.6;">
                    <strong>${labels[type]}:</strong> ${text.replace(/\n/g, '<br>')}
                </div>
                <p><br></p>
            `;
            
            // Clean form
            document.getElementById('widgetCalloutText').value = '';
            
        } else if (activeWidgetType === 'button') {
            const label = document.getElementById('widgetBtnLabel').value.trim();
            const url = document.getElementById('widgetBtnUrl').value.trim();
            const btnStyle = document.getElementById('widgetBtnStyle').value;
            const align = document.getElementById('widgetBtnAlign').value;
            
            if (!label || !url) {
                alert('Silakan isi nama label dan alamat link tujuan.');
                return;
            }
            
            const styleColors = {
                'primary': 'var(--primary)',
                'secondary': 'var(--brand-teal-mid)',
                'brand': 'var(--brand-teal-deep)',
                'success': '#10b981'
            };
            
            html = `
                <div style="text-align: ${align}; margin: 25px 0;">
                    <a href="${url}" class="btn btn-content" target="_blank" style="display: inline-block; padding: 10px 24px; font-weight: 600; text-decoration: none; border-radius: 6px; background-color: ${styleColors[btnStyle]}; color: white; transition: opacity 0.2s;">${label}</a>
                </div>
                <p><br></p>
            `;
            
            // Clean form
            document.getElementById('widgetBtnLabel').value = '';
            document.getElementById('widgetBtnUrl').value = '';
        }
        
        if (html) {
            // Paste HTML at the current cursor selection or append
            const range = quill.getSelection();
            if (range) {
                quill.clipboard.dangerouslyPasteHTML(range.index, html);
                quill.setSelection(range.index + html.length + 1);
            } else {
                quill.clipboard.dangerouslyPasteHTML(quill.getLength(), html);
            }
            
            closeWidgetModal();
            showToast('Widget berhasil disisipkan.', 'success');
        }
    };

    window.triggerGalleryFileInput = function() {
        document.getElementById('galleryUploadFiles').click();
    };

    window.uploadGalleryImages = function(input) {
        const files = input.files;
        if (!files.length) return;

        const statusEl = document.getElementById('galleryUploadStatus');
        statusEl.style.display = 'block';
        statusEl.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Mengunggah ${files.length} gambar...`;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const uploadUrl = '{{ route("admin.upload.image") }}';

        let uploadedCount = 0;
        let errors = [];

        const uploadPromises = Array.from(files).map(file => {
            const formData = new FormData();
            formData.append('image', file);

            return fetch(uploadUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    uploadedCount++;
                    const textarea = document.getElementById('widgetGalleryUrls');
                    const currentValue = textarea.value.trim();
                    textarea.value = (currentValue ? currentValue + '\n' : '') + data.url;
                } else {
                    errors.push(data.message || 'Gagal');
                }
            })
            .catch(err => {
                errors.push('Error jaringan');
                console.error(err);
            });
        });

        Promise.all(uploadPromises).then(() => {
            statusEl.style.display = 'block';
            if (errors.length > 0) {
                statusEl.innerHTML = `<span style="color:var(--danger)"><i class="fa-solid fa-circle-exclamation"></i> Terunggah: ${uploadedCount}, Gagal: ${errors.length}</span>`;
            } else {
                statusEl.innerHTML = `<span style="color:var(--success)"><i class="fa-solid fa-circle-check"></i> Sukses mengunggah ${uploadedCount} gambar!</span>`;
            }
            
            input.value = '';
            
            setTimeout(() => {
                statusEl.style.display = 'none';
            }, 3000);
        });
    };

    // Setup Drag and Drop events for Gallery
    document.addEventListener('DOMContentLoaded', () => {
        const galDropZone = document.getElementById('galleryUploadZone');
        if (galDropZone) {
            ['dragenter', 'dragover'].forEach(eventName => {
                galDropZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    galDropZone.style.borderColor = 'var(--primary)';
                    galDropZone.style.backgroundColor = '#f0f4f8';
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                galDropZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    galDropZone.style.borderColor = '#ccc';
                    galDropZone.style.backgroundColor = '#fafafa';
                }, false);
            });

            galDropZone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                const fileInput = document.getElementById('galleryUploadFiles');
                
                fileInput.files = files;
                uploadGalleryImages(fileInput);
            });
        }
    });
</script>
@endsection
