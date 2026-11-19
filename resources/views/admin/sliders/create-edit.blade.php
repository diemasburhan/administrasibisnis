@extends('layouts.admin')

@section('title', isset($slider) ? 'Edit Slider - Admin LPKIA' : 'Tambah Slider Baru - Admin LPKIA')
@section('page_title', isset($slider) ? 'Edit Data Slider' : 'Tambah Slider Baru')

@section('content')

    <div style="max-width: 600px; margin: 0 auto;">
        <div class="editor-main-area" style="min-height: auto;">
            <form action="{{ isset($slider) ? route('admin.sliders.update', $slider->id) : route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($slider))
                    @method('PUT')
                @endif

                <!-- Judul Slider -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="title" style="font-weight: 700; color: var(--primary-dark); display: block; margin-bottom: 8px;">Judul Slider (Title)</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Contoh: Kampus Unggul & Profesional" value="{{ old('title', $slider->title ?? '') }}" style="width: 100%; height: 42px;">
                    @error('title')
                        <span style="color: var(--danger); font-size: 0.8rem; font-weight: 600; display: block; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Deskripsi Slider -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="description" style="font-weight: 700; color: var(--primary-dark); display: block; margin-bottom: 8px;">Deskripsi / Caption</label>
                    <textarea name="description" id="description" rows="3" class="form-control" placeholder="Masukkan deskripsi singkat..." style="width: 100%; resize: vertical;">{{ old('description', $slider->description ?? '') }}</textarea>
                    @error('description')
                        <span style="color: var(--danger); font-size: 0.8rem; font-weight: 600; display: block; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Urutan Tampilan (Sort Order) -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="sort_order" style="font-weight: 700; color: var(--primary-dark); display: block; margin-bottom: 8px;">Urutan Tampilan</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-control" placeholder="Contoh: 1" value="{{ old('sort_order', $slider->sort_order ?? '0') }}" required style="width: 100%; height: 42px;">
                    @error('sort_order')
                        <span style="color: var(--danger); font-size: 0.8rem; font-weight: 600; display: block; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Upload Media (Image/Video) -->
                <div class="form-group" style="margin-bottom: 30px;">
                    <label style="font-weight: 700; color: var(--primary-dark); display: block; margin-bottom: 8px;">Berkas Media (Gambar atau Video)</label>
                    
                    @if(isset($slider) && $slider->file_path)
                        <div style="margin-bottom: 15px;">
                            @if($slider->file_type === 'video')
                                <video src="{{ asset($slider->file_path) }}" controls style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color);"></video>
                            @else
                                <img src="{{ asset($slider->file_path) }}" alt="{{ $slider->title }}" style="width: 150px; max-height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color);">
                            @endif
                            <p style="font-size: 0.8rem; color: var(--text-light); margin-top: 2px;">Media saat ini (Tipe: {{ ucfirst($slider->file_type) }})</p>
                        </div>
                    @endif

                    <div class="image-upload-box" id="uploadBox">
                        <i class="fa-solid fa-cloud-arrow-up image-upload-icon"></i>
                        <div class="image-upload-text">Klik untuk memilih gambar (PNG, JPG, GIF) atau video (MP4, WEBM, max 100MB)</div>
                        <input type="file" name="media_file" id="mediaFile" style="position: absolute; top:0; left:0; width:100%; height:100%; opacity:0; cursor:pointer;" accept="image/*,video/*" {{ isset($slider) ? '' : 'required' }}>
                    </div>
                    <div id="fileFeedback" style="font-size: 0.82rem; color: var(--success); font-weight: 600; margin-top: 8px; display: none;"></div>
                    @error('media_file')
                        <span style="color: var(--danger); font-size: 0.8rem; font-weight: 600; display: block; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 10px; justify-content: flex-end; border-top: 1px solid var(--border-color); padding-top: 20px;">
                    <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline" style="height: 40px; line-height: 24px;">Batal</a>
                    <button type="submit" class="btn btn-primary" style="height: 40px;">Simpan Slider</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    const mediaInput = document.getElementById('mediaFile');
    const uploadBox = document.getElementById('uploadBox');
    const feedback = document.getElementById('fileFeedback');

    mediaInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            const file = e.target.files[0];
            feedback.innerText = `Terpilih: ${file.name} (${(file.size/1024/1024).toFixed(2)} MB)`;
            feedback.style.display = 'block';
            uploadBox.style.borderColor = 'var(--success)';
            uploadBox.style.backgroundColor = 'rgba(16, 185, 129, 0.02)';
        }
    });

    // Drag and Drop enhancements
    uploadBox.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadBox.classList.add('dragover');
    });
    uploadBox.addEventListener('dragleave', () => {
        uploadBox.classList.remove('dragover');
    });
    uploadBox.addEventListener('drop', () => {
        uploadBox.classList.remove('dragover');
    });
</script>
@endsection
