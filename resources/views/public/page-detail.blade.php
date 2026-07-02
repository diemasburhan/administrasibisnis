@extends('layouts.public')

@section('title', $page->title . ' - Administrasi Bisnis LPKIA')

@section('styles')
<style>
    .page-detail-wrapper {
        background-color: var(--canvas);
        border-radius: var(--rounded-lg);
        padding: 50px 40px;
        box-shadow: var(--shadow-2);
        border: 1px solid var(--hairline);
        margin-top: 40px;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }
    .page-featured-image {
        width: 100%;
        max-height: 420px;
        object-fit: cover;
        border-radius: var(--rounded-md);
        margin-bottom: 40px;
        box-shadow: var(--shadow-1);
    }
    .page-body-content {
        font-size: 1.05rem;
        line-height: 1.8;
        color: var(--charcoal);
    }
    .page-body-content p {
        margin-bottom: 22px;
    }
    .page-body-content h2, .page-body-content h3 {
        color: var(--brand-teal-mid);
        margin-top: 30px;
        margin-bottom: 15px;
        font-weight: 600;
    }
    .page-body-content h2 {
        font-size: 1.6rem;
        border-bottom: 2px solid var(--hairline-soft);
        padding-bottom: 8px;
    }
    .page-body-content h3 {
        font-size: 1.25rem;
    }
    .page-body-content ul, .page-body-content ol {
        margin-left: 20px;
        margin-bottom: 20px;
    }
    .page-body-content li {
        margin-bottom: 8px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="page-detail-wrapper">
        <h2 style="font-size: 2.2rem; font-weight: 500; color: var(--ink); margin-bottom: 30px; border-bottom: 3px solid var(--primary); padding-bottom: 12px; letter-spacing: -0.5px;">
            {{ $page->title }}
        </h2>
        
        @if($page->featured_image)
            <img src="{{ asset($page->featured_image) }}" alt="{{ $page->title }}" class="page-featured-image">
        @endif

        <div class="page-body-content">
            {!! $page->content !!}
        </div>
    </div>
</div>
@endsection
