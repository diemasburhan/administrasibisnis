@extends('layouts.public')

@section('title', 'Berita & Pengumuman - Administrasi Bisnis LPKIA')

@section('styles')
<style>
    .posts-page-header {
        background-color: var(--brand-teal-deep);
        color: white;
        padding: 50px 0;
        text-align: center;
        margin-bottom: 50px;
        position: relative;
        overflow: hidden;
        border-bottom: 3px solid var(--primary);
    }
    .posts-page-header h2 {
        font-size: 2.2rem;
        font-weight: 500;
        margin-bottom: 10px;
        letter-spacing: -0.5px;
    }
    .posts-page-header p {
        font-size: 1rem;
        color: var(--on-dark-muted);
        max-width: 600px;
        margin: 0 auto;
    }
    .pagination-wrapper {
        margin-top: 50px;
        display: flex;
        justify-content: center;
    }
    .pagination {
        display: flex;
        list-style: none;
        padding-left: 0;
        gap: 8px;
    }
    .page-item .page-link {
        display: block;
        padding: 10px 18px;
        color: var(--ink);
        background-color: var(--canvas);
        border: 1px solid var(--hairline-strong);
        text-decoration: none;
        border-radius: var(--rounded-md);
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 150ms ease;
    }
    .page-item.active .page-link, .page-item .page-link:hover {
        background-color: var(--primary);
        color: var(--on-primary);
        border-color: var(--primary);
    }
    .page-item.disabled .page-link {
        color: var(--muted);
        pointer-events: none;
        background-color: var(--surface-soft);
        border-color: var(--hairline);
    }
</style>
@endsection

@section('content')
    <!-- Page Header Banner -->
    <div class="posts-page-header">
        <div class="container">
            <h2>Berita & Informasi Terbaru</h2>
            <p>Ikuti perkembangan terbaru, kegiatan kemahasiswaan, wirausaha mahasiswa, serta pengumuman resmi Program Studi Administrasi Bisnis LPKIA.</p>
        </div>
    </div>

    <!-- Posts Grid Listing -->
    <section class="content-section" style="padding-top: 0;">
        <div class="container">
            <div class="grid-3-col">
                @forelse($posts as $post)
                    <article class="post-card" style="background-color: var(--bg-white);">
                        @if($post->featured_image)
                            <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="post-card-img">
                        @else
                            <div class="post-card-img" style="display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.25);">
                                <i class="fa-solid fa-image fa-3x"></i>
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
                    <div style="grid-column: 1 / -1; text-align: center; padding: 60px; color: var(--text-muted);">
                        <i class="fa-regular fa-folder-open fa-3x" style="margin-bottom: 15px; display: block;"></i>
                        Belum ada berita yang diterbitkan saat ini.
                    </div>
                @endforelse
            </div>

            <!-- Custom Styled Pagination -->
            @if($posts->hasPages())
                <div class="pagination-wrapper">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
