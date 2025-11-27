@extends('layouts.app')

@section('title', $post->meta_title ?: $post->title)

@section('meta')
    <title>{{ $post->meta_title ?: $post->title }} &mdash; {{ $appSettings['site_name'] ?? config('app.name') }}</title>
    @if($post->meta_description)
        <meta name="description" content="{{ $post->meta_description }}">
    @elseif($post->excerpt)
        <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($post->excerpt), 160) }}">
    @endif
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ request()->url() }}">
    @if($post->featured_image_path)
        <meta property="og:image" content="{{ asset('storage/'.$post->featured_image_path) }}">
    @endif
    @if($post->meta_description)
        <meta property="og:description" content="{{ $post->meta_description }}">
    @endif
@endsection

@push('styles')
    <style>
        .blog-detail-hero {
            background: linear-gradient(94deg, #eef4ff 2%, #fff5fb 52%, #effaff 98%);
            border-radius: 26px;
            padding: clamp(2.5rem, 5vw, 4rem);
        }
        .blog-detail-hero .badge {
            border-radius: 999px;
            background: rgba(2, 69, 80, 0.1);
            color: #024550;
            font-weight: 600;
            padding: 0.4rem 0.9rem;
        }
        .blog-detail-image {
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 65px -35px rgba(16, 24, 40, 0.45);
        }
        .blog-detail-body {
            max-width: 860px;
            margin: 0 auto;
            font-size: 1.05rem;
            line-height: 1.9;
            color: #353b4a;
        }
        .blog-detail-body h2,
        .blog-detail-body h3,
        .blog-detail-body h4 {
            margin-top: 2.5rem;
            margin-bottom: 1.25rem;
            font-weight: 600;
        }
        .blog-detail-body p {
            margin-bottom: 1.6rem;
        }
        .blog-detail-body img {
            max-width: 100%;
            border-radius: 18px;
            margin: 2rem auto;
            display: block;
        }
        .blog-tag {
            border-radius: 999px;
            background: rgba(2, 69, 80, 0.08);
            color: #024550;
            padding: 0.3rem 0.75rem;
            text-decoration: none;
            font-size: 0.9rem;
            margin-right: 0.5rem;
        }
        .blog-related-card {
            border-radius: 20px;
            border: 1px solid rgba(15, 23, 42, 0.08);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .blog-related-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 40px -30px rgba(15, 23, 42, 0.5);
        }
        .blog-related-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }
    </style>
@endpush

@section('content')
    <section class="py-5 py-lg-6">
        <div class="container">
            <div class="blog-detail-hero mb-5">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <div class="d-flex align-items-center gap-3 flex-wrap mb-3">
                            <a href="{{ route('blog.index') }}" class="text-secondary text-decoration-none d-inline-flex align-items-center gap-2">
                                <iconify-icon icon="solar:arrow-left-linear"></iconify-icon>
                                Back to Blog
                            </a>
                            @if($post->category)
                                <a href="{{ route('blog.category', $post->category->slug) }}" class="badge text-decoration-none">{{ $post->category->name }}</a>
                            @endif
                        </div>
                        <h1 class="display-5 fw-semibold text-dark mb-4">{{ $post->title }}</h1>
                        <div class="d-flex align-items-center gap-4 text-secondary">
                            @if($post->author)
                                <span>By {{ $post->author->name }}</span>
                            @endif
                            <span>{{ optional($post->published_at)->format('F d, Y') }}</span>
                            <span>{{ $readingTime }} min read</span>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="blog-detail-image">
                            <img
                                src="{{ $post->featured_image_path ? asset('storage/'.$post->featured_image_path) : asset('shopus/assets/images/homepage-one/blog-4.webp') }}"
                                alt="{{ $post->featured_image_alt ?? $post->title }}"
                                class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>

            <article class="blog-detail-body">
                {!! $post->content !!}
            </article>

            @if($post->tags->isNotEmpty())
                <div class="mt-5">
                    <h5 class="fw-semibold mb-3">Tags</h5>
                    @foreach($post->tags as $tag)
                        <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}" class="blog-tag">{{ $tag->name }}</a>
                    @endforeach
                </div>
            @endif

            @if($relatedPosts->isNotEmpty())
                <div class="mt-5 pt-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h4 class="fw-semibold mb-0">Related Articles</h4>
                        <a href="{{ route('blog.index') }}" class="text-decoration-none text-primary fw-semibold">View all</a>
                    </div>
                    <div class="row g-4">
                        @foreach($relatedPosts as $related)
                            <div class="col-md-6 col-xl-4">
                                <a href="{{ route('blog.show', $related->slug) }}" class="text-decoration-none text-reset">
                                    <div class="blog-related-card">
                                        <img src="{{ $related->featured_image_path ? asset('storage/'.$related->featured_image_path) : asset('shopus/assets/images/homepage-one/blog-1.webp') }}" alt="{{ $related->featured_image_alt ?? $related->title }}">
                                        <div class="p-3">
                                            @if($related->category)
                                                <span class="badge bg-neutral-100 text-secondary mb-2">{{ $related->category->name }}</span>
                                            @endif
                                            <h5 class="fw-semibold text-dark">{{ $related->title }}</h5>
                                            <p class="text-secondary mb-0">{{ \Illuminate\Support\Str::limit($related->excerpt ?? strip_tags($related->content), 90) }}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

