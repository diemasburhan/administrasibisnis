@extends('layouts.admin')

@section('title', 'Manajer Jadwal Kelas - Admin Panel')
@section('page_title', 'Pengelola Jadwal Kelas')

@section('styles')
<style>
    .schedule-manager-container {
        max-width: 1000px;
        margin-top: 15px;
    }
    .schedule-card {
        background-color: var(--bg-white);
        border: 1px solid var(--hairline);
        border-radius: var(--rounded-lg);
        padding: 30px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 25px;
    }
    .schedule-header {
        border-bottom: 1px solid var(--hairline);
        padding-bottom: 15px;
        margin-bottom: 25px;
    }
    .schedule-header h3 {
        font-size: 1.3rem;
        color: var(--primary-deep);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .schedule-header p {
        margin: 5px 0 0 0;
        color: var(--muted);
        font-size: 0.9rem;
    }
    /* Row Builder Styles */
    .schedule-item-row {
        background-color: var(--bg-light);
        border: 1px solid var(--hairline-strong);
        border-radius: var(--rounded-md);
        padding: 20px;
        margin-bottom: 15px;
        position: relative;
        transition: all 0.2s ease;
    }
    .schedule-item-row:hover {
        border-color: var(--primary);
        box-shadow: var(--shadow-sm);
    }
    .schedule-item-row .btn-remove {
        position: absolute;
        top: 20px;
        right: 20px;
        color: var(--danger);
        cursor: pointer;
        background: none;
        border: none;
        font-size: 1.2rem;
        transition: transform 0.2s;
    }
    .schedule-item-row .btn-remove:hover {
        transform: scale(1.15);
    }
    .schedule-fields-grid {
        display: grid;
        grid-template-columns: 140px 160px 1fr 1fr;
        gap: 15px;
        padding-right: 35px;
    }
    .schedule-fields-subgrid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-top: 15px;
        padding-right: 35px;
    }
    .btn-add-row {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 5px;
    }
    @media (max-width: 768px) {
        .schedule-fields-grid {
            grid-template-columns: 1fr;
            padding-right: 0;
        }
        .schedule-fields-subgrid {
            grid-template-columns: 1fr;
            padding-right: 0;
        }
        .schedule-item-row .btn-remove {
            top: 10px;
            right: 10px;
        }
    }
</style>
@endsection

@section('content')
<div class="schedule-manager-container">
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.schedule.update') }}" method="POST" id="scheduleForm">
        @csrf

        <!-- SECTION 1: HEADER & GENERAL CONFIG -->
        <div class="schedule-card">
            <div class="schedule-header">
                <h3><i class="fa-solid fa-gears"></i> Konfigurasi Header Jadwal</h3>
                <p>Ubah judul dan teks penjelasan tabel jadwal di halaman depan portal.</p>
            </div>

            <div class="form-group">
                <label for="schedule_title">Judul Section Jadwal</label>
                <input type="text" name="schedule_title" id="schedule_title" class="form-control" value="{{ $schedule['schedule_title'] }}" required>
            </div>

            <div class="form-group">
                <label for="schedule_subtitle">Keterangan / Sub-Judul</label>
                <textarea name="schedule_subtitle" id="schedule_subtitle" rows="2" class="form-control" required>{{ $schedule['schedule_subtitle'] }}</textarea>
            </div>
        </div>

        <!-- SECTION 2: SCHEDULE ROWS BUILDER -->
        <div class="schedule-card">
            <div class="schedule-header">
                <h3><i class="fa-solid fa-list-check"></i> Daftar Baris Jadwal Kelas</h3>
                <p>Kelola data mata kuliah praktikum, dosen, waktu, dan ruangan kelas.</p>
            </div>

            <div id="scheduleRowsContainer">
                @foreach($schedule['schedule_rows'] as $index => $row)
                    <div class="schedule-item-row" id="row-{{ $index }}">
                        <button type="button" class="btn-remove" onclick="removeScheduleRow('row-{{ $index }}')" title="Hapus jadwal">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                        
                        <div class="schedule-fields-grid">
                            <div>
                                <label>Hari</label>
                                <input type="text" name="rows[{{ $index }}][day]" class="form-control" value="{{ $row['day'] }}" placeholder="Senin" required>
                            </div>
                            <div>
                                <label>Jam / Waktu</label>
                                <input type="text" name="rows[{{ $index }}][time]" class="form-control" value="{{ $row['time'] }}" placeholder="08:00 - 10:30" required>
                            </div>
                            <div>
                                <label>Mata Kuliah</label>
                                <input type="text" name="rows[{{ $index }}][course]" class="form-control" value="{{ $row['course'] }}" placeholder="Aplikasi Perkantoran Digital" required>
                            </div>
                            <div>
                                <label>Dosen Pengampu</label>
                                <input type="text" name="rows[{{ $index }}][lecturer]" class="form-control" value="{{ $row['lecturer'] }}" placeholder="Nama Dosen, M.M." required>
                            </div>
                        </div>

                        <div class="schedule-fields-subgrid">
                            <div>
                                <label>Ruangan / Laboratorium</label>
                                <input type="text" name="rows[{{ $index }}][room]" class="form-control" value="{{ $row['room'] }}" placeholder="Lab Komputer Bisnis 3" required>
                            </div>
                            <div>
                                <label>Warna Penanda Badge</label>
                                <select name="rows[{{ $index }}][badge_type]" class="form-control">
                                    <option value="success" {{ $row['badge_type'] === 'success' ? 'selected' : '' }}>Hijau (Praktikum / Aktif)</option>
                                    <option value="secondary" {{ $row['badge_type'] === 'secondary' ? 'selected' : '' }}>Biru (Teori / Umum)</option>
                                    <option value="warning" {{ $row['badge_type'] === 'warning' ? 'selected' : '' }}>Oranye (Kewirausahaan / Khusus)</option>
                                    <option value="danger" {{ $row['badge_type'] === 'danger' ? 'selected' : '' }}>Merah (Urgent / Penting)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px;">
                <button type="button" class="btn btn-secondary btn-add-row" onclick="addNewScheduleRow()">
                    <i class="fa-solid fa-plus"></i> Tambah Baris Jadwal
                </button>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Jadwal
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    let scheduleRowCount = {{ count($schedule['schedule_rows']) }};

    function removeScheduleRow(rowId) {
        if (confirm('Apakah Anda yakin ingin menghapus baris jadwal ini?')) {
            const rowElement = document.getElementById(rowId);
            if (rowElement) {
                rowElement.remove();
            }
        }
    }

    function addNewScheduleRow() {
        const container = document.getElementById('scheduleRowsContainer');
        const rowId = `row-${scheduleRowCount}`;
        
        const html = `
            <div class="schedule-item-row" id="${rowId}">
                <button type="button" class="btn-remove" onclick="removeScheduleRow('${rowId}')" title="Hapus jadwal">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
                
                <div class="schedule-fields-grid">
                    <div>
                        <label>Hari</label>
                        <input type="text" name="rows[${scheduleRowCount}][day]" class="form-control" placeholder="Senin" required>
                    </div>
                    <div>
                        <label>Jam / Waktu</label>
                        <input type="text" name="rows[${scheduleRowCount}][time]" class="form-control" placeholder="08:00 - 10:30" required>
                    </div>
                    <div>
                        <label>Mata Kuliah</label>
                        <input type="text" name="rows[${scheduleRowCount}][course]" class="form-control" placeholder="Aplikasi Perkantoran Digital" required>
                    </div>
                    <div>
                        <label>Dosen Pengampu</label>
                        <input type="text" name="rows[${scheduleRowCount}][lecturer]" class="form-control" placeholder="Nama Dosen, M.M." required>
                    </div>
                </div>

                <div class="schedule-fields-subgrid">
                    <div>
                        <label>Ruangan / Laboratorium</label>
                        <input type="text" name="rows[${scheduleRowCount}][room]" class="form-control" placeholder="Lab Komputer Bisnis 3" required>
                    </div>
                    <div>
                        <label>Warna Penanda Badge</label>
                        <select name="rows[${scheduleRowCount}][badge_type]" class="form-control">
                            <option value="success">Hijau (Praktikum / Aktif)</option>
                            <option value="secondary">Biru (Teori / Umum)</option>
                            <option value="warning">Oranye (Kewirausahaan / Khusus)</option>
                            <option value="danger">Merah (Urgent / Penting)</option>
                        </select>
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', html);
        scheduleRowCount++;
    }
</script>
@endsection
