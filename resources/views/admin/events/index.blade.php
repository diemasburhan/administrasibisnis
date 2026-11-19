@extends('layouts.admin')

@section('title', 'Event & Kegiatan - Admin Panel')
@section('page_title', 'Event & Kegiatan')

@section('styles')
<style>
    .events-manager-container {
        max-width: 1000px;
        margin: 0 auto;
    }
    .events-card {
        background: var(--white);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        padding: 25px;
        margin-bottom: 25px;
    }
    .events-header {
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    .events-header h3 {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--primary-dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .events-header p {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin: 5px 0 0 0;
    }
    .event-item-row {
        background: var(--bg-light);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        padding: 15px;
        margin-bottom: 12px;
        position: relative;
        transition: all 0.2s;
    }
    .event-item-row:hover {
        border-color: var(--primary-light);
    }
    .event-item-row .btn-remove {
        position: absolute;
        top: 12px;
        right: 12px;
        background: none;
        border: none;
        color: var(--danger);
        cursor: pointer;
        font-size: 1.1rem;
        opacity: 0.7;
    }
    .event-item-row .btn-remove:hover {
        opacity: 1;
    }
    .event-fields-grid {
        display: grid;
        grid-template-columns: 90px 110px 1fr 1fr;
        gap: 12px;
        padding-right: 30px;
    }
    .btn-add-row {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
    }
    @media (max-width: 768px) {
        .event-fields-grid {
            grid-template-columns: 1fr;
            padding-right: 0;
        }
        .event-item-row .btn-remove {
            top: 8px;
            right: 8px;
        }
    }
</style>
@endsection

@section('content')
<div class="events-manager-container">
    <div style="display: flex; justify-content: flex-end; margin-bottom: 15px;">
        <button type="button" class="btn btn-secondary btn-sm" onclick="addNewEventRow()">
            <i class="fa-solid fa-plus"></i> Tambah Event / Kegiatan Baru
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.events.update') }}" method="POST" id="eventsForm">
        @csrf

        <!-- SECTION 1: HEADER CONFIG -->
        <div class="events-card">
            <div class="events-header">
                <h3><i class="fa-solid fa-gears"></i> Konfigurasi Judul Agenda & Event</h3>
                <p>Atur judul kolom Agenda & Event yang tampil pada halaman utama portal.</p>
            </div>

            <div class="form-group">
                <label for="col2_title">Judul Kolom Agenda & Event</label>
                <input type="text" name="col2_title" id="col2_title" class="form-control" value="{{ old('col2_title', $eventTitle) }}" required>
            </div>
        </div>

        <!-- SECTION 2: EVENTS & KEGIATAN LIST -->
        <div class="events-card">
            <div class="events-header">
                <h3><i class="fa-solid fa-calendar-check"></i> Daftar Event & Kegiatan</h3>
                <p>Perbarui tanggal, bulan, judul, serta waktu dan lokasi event atau kegiatan.</p>
            </div>

            <div id="eventsContainer">
                @foreach($events as $index => $event)
                    <div class="event-item-row" id="event-row-{{ $index }}">
                        <button type="button" class="btn-remove" onclick="removeEventRow('event-row-{{ $index }}')" title="Hapus event">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>

                        <div class="event-fields-grid">
                            <div>
                                <label>Tanggal</label>
                                <input type="text" name="events[{{ $index }}][day]" class="form-control" value="{{ $event['day'] ?? '' }}" placeholder="15" required>
                            </div>
                            <div>
                                <label>Bulan</label>
                                <input type="text" name="events[{{ $index }}][month]" class="form-control" value="{{ $event['month'] ?? '' }}" placeholder="Jul" required>
                            </div>
                            <div>
                                <label>Nama Event / Kegiatan</label>
                                <input type="text" name="events[{{ $index }}][title]" class="form-control" value="{{ $event['title'] ?? '' }}" placeholder="Workshop Business Plan" required>
                            </div>
                            <div>
                                <label>Waktu & Lokasi</label>
                                <input type="text" name="events[{{ $index }}][meta]" class="form-control" value="{{ $event['meta'] ?? '' }}" placeholder="09:00 - selesai | Aula LPKIA" required>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-secondary btn-add-row" onclick="addNewEventRow()">
                <i class="fa-solid fa-plus"></i> Tambah Baris Event / Kegiatan
            </button>
        </div>

        <!-- SUBMIT BUTTON -->
        <div style="display: flex; justify-content: flex-end; gap: 15px; margin-bottom: 40px;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-weight: 700;">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Event & Kegiatan
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    let eventRowCount = {{ count($events) }};

    function removeEventRow(rowId) {
        if (confirm('Hapus item event/kegiatan ini?')) {
            const el = document.getElementById(rowId);
            if (el) {
                el.remove();
            }
        }
    }

    function addNewEventRow() {
        const container = document.getElementById('eventsContainer');
        const rowId = `event-row-${eventRowCount}`;

        const html = `
            <div class="event-item-row" id="${rowId}">
                <button type="button" class="btn-remove" onclick="removeEventRow('${rowId}')" title="Hapus event">
                    <i class="fa-solid fa-trash-can"></i>
                </button>

                <div class="event-fields-grid">
                    <div>
                        <label>Tanggal</label>
                        <input type="text" name="events[${eventRowCount}][day]" class="form-control" placeholder="15" required>
                    </div>
                    <div>
                        <label>Bulan</label>
                        <input type="text" name="events[${eventRowCount}][month]" class="form-control" placeholder="Jul" required>
                    </div>
                    <div>
                        <label>Nama Event / Kegiatan</label>
                        <input type="text" name="events[${eventRowCount}][title]" class="form-control" placeholder="Workshop Business Plan" required>
                    </div>
                    <div>
                        <label>Waktu & Lokasi</label>
                        <input type="text" name="events[${eventRowCount}][meta]" class="form-control" placeholder="09:00 - selesai | Aula LPKIA" required>
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', html);
        eventRowCount++;
    }
</script>
@endsection

