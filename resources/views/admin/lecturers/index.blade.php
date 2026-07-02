@extends('layouts.admin')

@section('title', 'Manajemen Dosen - Admin LPKIA')
@section('page_title', 'Dosen Ahli & Pengajar')

@section('content')

    <div style="display: flex; justify-content: flex-end; margin-bottom: 10px;">
        <a href="{{ route('admin.lecturers.create') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-plus"></i> Tambah Dosen Baru
        </a>
    </div>

    <div class="table-card">
        <div class="table-toolbar">
            <div></div> <!-- Spacer -->

            <!-- Search and Filter Form -->
            <form action="{{ route('admin.lecturers.index') }}" method="GET" class="table-filters">
                <input type="text" name="search" class="form-control table-search" placeholder="Cari nama dosen..." value="{{ request('search') }}" style="height: 38px;">
                
                <select name="expertise" class="form-control" style="width: 220px; height: 38px; padding: 6px 12px; font-size: 0.85rem;" onchange="this.form.submit()">
                    <option value="">Semua Bidang Keahlian</option>
                    <option value="office" {{ request('expertise') === 'office' ? 'selected' : '' }}>Administrasi Perkantoran</option>
                    <option value="entrepreneur" {{ request('expertise') === 'entrepreneur' ? 'selected' : '' }}>Kewirausahaan</option>
                    <option value="digital" {{ request('expertise') === 'digital' ? 'selected' : '' }}>Bisnis Digital</option>
                </select>

                @if(request('search') || request('expertise'))
                    <a href="{{ route('admin.lecturers.index') }}" class="btn btn-outline btn-sm" style="height: 38px;" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 80px; text-align: center;">Foto</th>
                        <th>Nama Dosen</th>
                        <th>Jabatan & NIDN</th>
                        <th>Keahlian</th>
                        <th>Tanggal Terdaftar</th>
                        <th style="width: 120px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lecturers as $lecturer)
                        <tr>
                            <td style="text-align: center;">
                                @if($lecturer->photo)
                                    <img src="{{ asset($lecturer->photo) }}" alt="{{ $lecturer->name }}" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border-color);">
                                @else
                                    <div style="width: 48px; height: 48px; border-radius: 50%; background-color: #E2E8F0; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: var(--text-light);">
                                        <i class="fa-solid fa-user-circle fa-2x"></i>
                                    </div>
                                @endif
                            </td>
                            <td style="font-weight: 600;">
                                <a href="{{ route('admin.lecturers.edit', $lecturer->id) }}" style="color: var(--primary);">
                                    {{ $lecturer->name }}
                                </a>
                            </td>
                            <td>
                                <div style="font-size: 0.9rem; font-weight: 500;">{{ $lecturer->position ?? '-' }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">NIDN: {{ $lecturer->nidn ?? '-' }}</div>
                            </td>
                            <td>
                                @if($lecturer->expertise === 'office')
                                    <span class="badge" style="background-color: #ece0fd; color: var(--accent-purple);"><i class="fa-solid fa-briefcase"></i> Adm Perkantoran</span>
                                @elseif($lecturer->expertise === 'entrepreneur')
                                    <span class="badge" style="background-color: #ffebe5; color: var(--accent-orange);"><i class="fa-solid fa-lightbulb"></i> Kewirausahaan</span>
                                @elseif($lecturer->expertise === 'digital')
                                    <span class="badge" style="background-color: #e5ebff; color: var(--accent-blue);"><i class="fa-solid fa-store"></i> Bisnis Digital</span>
                                @else
                                    <span class="badge" style="background-color: var(--surface-soft); color: var(--slate);"><i class="fa-solid fa-user"></i> Pengajar</span>
                                @endif
                            </td>
                            <td style="font-size: 0.82rem; color: var(--text-muted);">
                                {{ $lecturer->created_at->format('d M Y') }}
                            </td>
                            <td style="text-align: center;">
                                <div class="table-actions" style="justify-content: center;">
                                    <a href="{{ route('admin.lecturers.edit', $lecturer->id) }}" class="action-link" title="Edit Dosen">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.lecturers.destroy', $lecturer->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dosen ini?')" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;" class="action-link delete" title="Hapus Dosen">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 30px; color: var(--text-muted);">
                                Belum ada data dosen terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($lecturers->hasPages())
            <div style="padding: 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end;">
                {{ $lecturers->links() }}
            </div>
        @endif
    </div>

@endsection
