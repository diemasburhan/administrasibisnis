@extends('layouts.admin')

@section('title', 'Manajemen Slider - Admin LPKIA')
@section('page_title', 'Kelola Slider Hero')

@section('content')

    <div style="display: flex; justify-content: flex-end; margin-bottom: 10px;">
        <a href="{{ route('admin.sliders.create') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-plus"></i> Tambah Slider Baru
        </a>
    </div>

    <div class="table-card">
        <div class="table-toolbar">
            <div></div> <!-- Spacer -->

            <!-- Search Form -->
            <form action="{{ route('admin.sliders.index') }}" method="GET" class="table-filters">
                <input type="text" name="search" class="form-control table-search" placeholder="Cari slider..." value="{{ request('search') }}" style="height: 38px;">

                @if(request('search'))
                    <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline btn-sm" style="height: 38px;" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 150px; text-align: center;">Media</th>
                        <th>Judul (Title)</th>
                        <th>Deskripsi</th>
                        <th style="width: 100px; text-align: center;">Tipe</th>
                        <th style="width: 100px; text-align: center;">Urutan</th>
                        <th style="width: 120px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sliders as $slider)
                        <tr>
                            <td style="text-align: center; vertical-align: middle;">
                                @if($slider->file_type === 'video')
                                    <video src="{{ asset($slider->file_path) }}" style="width: 120px; height: 75px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-color);" muted></video>
                                @else
                                    <img src="{{ asset($slider->file_path) }}" alt="{{ $slider->title }}" style="width: 120px; height: 75px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-color);">
                                @endif
                            </td>
                            <td style="font-weight: 600; vertical-align: middle;">
                                <a href="{{ route('admin.sliders.edit', $slider->id) }}" style="color: var(--primary);">
                                    {{ $slider->title ?? '(Tanpa Judul)' }}
                                </a>
                            </td>
                            <td style="vertical-align: middle;">
                                <div style="font-size: 0.9rem; max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $slider->description ?? '-' }}
                                </div>
                            </td>
                            <td style="text-align: center; vertical-align: middle;">
                                @if($slider->file_type === 'video')
                                    <span class="badge" style="background-color: #e5ebff; color: var(--accent-blue);"><i class="fa-solid fa-video"></i> Video</span>
                                @else
                                    <span class="badge" style="background-color: #ffebe5; color: var(--accent-orange);"><i class="fa-solid fa-image"></i> Gambar</span>
                                @endif
                            </td>
                            <td style="text-align: center; vertical-align: middle; font-weight: 600;">
                                {{ $slider->sort_order }}
                            </td>
                            <td style="text-align: center; vertical-align: middle;">
                                <div class="table-actions" style="justify-content: center;">
                                    <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="action-link" title="Edit Slider">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus slider ini?')" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;" class="action-link delete" title="Hapus Slider">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px; color: var(--text-muted);">
                                Belum ada data slider terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($sliders->hasPages())
            <div style="padding: 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end;">
                {{ $sliders->links() }}
            </div>
        @endif
    </div>

@endsection
