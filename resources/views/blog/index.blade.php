@extends('layouts.app')

@section('title', 'Blog')

@section('meta')
    <title>Blog &mdash; {{ $appSettings['site_name'] ?? config('app.name') }}</title>
    @if($searchTerm)
        <meta name="description" content="Search results for '{{ $searchTerm }}' on the {{ $appSettings['site_name'] ?? config('app.name') }} blog.">
    @elseif($appSettings['site_tagline'] ?? null)
        <meta name="description" content="{{ $appSettings['site_tagline'] }}">
    @endif
@endsection

@push('styles')
    <style>
        .blog-hero {
            background: linear-gradient(96deg, #eef8ff 6%, #fff5fb 48%, #f1f7ff 94%);
            border-radius: 24px;
            padding: clamp(2rem, 4vw, 3.5rem);
        }
        .blog-feature-card img {
            border-radius: 18px;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .blog-card {
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 20px 60px -30px rgba(16, 24, 40, 0.25);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .blog-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 28px 80px -30px rgba(16, 24, 40, 0.35);
        }
        .blog-card img {
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            width: 100%;
            height: 220px;
            object-fit: cover;
        }
        .blog-card-body {
            padding: 1.75rem;
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
            flex: 1;
        }
        .blog-card-body .badge {
            background: rgba(2, 69, 80, 0.1);
            color: #024550;
            border-radius: 999px;
            padding: 0.4rem 0.85rem;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .blog-card-footer {
            padding: 0 1.75rem 1.5rem;
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.9rem;
            color: #6f7787;
        }
        .blog-filter-bar {
            border-radius: 18px;
            background: #ffffff;
            box-shadow: 0 18px 50px -20px rgba(30, 41, 59, 0.25);
            padding: clamp(1.5rem, 3vw, 2rem);
        }
        .blog-filter-bar .form-control,
        .blog-filter-bar .form-select {
            border-radius: 14px;
            border: 1px solid #d7dee8;
            padding: 0.8rem 1rem;
        }
        .blog-no-results {
            border-radius: 22px;
            background: #ffffff;
            padding: 3rem;
            text-align: center;
            box-shadow: inset 0 0 0 1px rgba(2, 69, 80, 0.08);
        }
        @media (max-width: 992px) {
            .blog-card img {
                height: 200px;
            }
        }
        @media (max-width: 576px) {
            .blog-card img {
                height: 180px;
            }
        }
    </style>
@endpush

@section('content')
    <section class="py-5 py-lg-6">
        <div class="container">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="flex items-center gap-2 caption2 text-secondary">
                    <li class="flex items-center gap-2">
                        <a href="{{ route('home') }}" class="duration-300 hover:text-green text-black">Home</a>
                        <span>/</span>
                    </li>
                    <li class="text-black">Blog</li>
                </ol>
            </nav>
            <div class="blog-hero mb-5">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-6">
                        <h1 class="display-5 fw-semibold text-dark mb-3">Stories for the Style Obsessed</h1>
                        <p class="lead text-secondary">
                            Discover trend reports, styling tips, and behind-the-scenes highlights curated by our in-house editors.
                        </p>
                        <div class="d-flex flex-wrap gap-3 mt-4">
                            <a href="#blog-articles" class="btn btn-primary px-4 py-2 radius-12">Browse Articles</a>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary px-4 py-2 radius-12">Shop Latest</a>
                        </div>
                    </div>
                    @if($featuredPost)
                        <div class="col-lg-6">
                            <a href="{{ route('blog.show', $featuredPost->slug) }}" class="text-decoration-none text-reset">
                                <div class="blog-feature-card overflow-hidden">
                                    <div class="position-relative">
                                        <img src="{{ $featuredPost->featured_image_path ? asset('storage/'.$featuredPost->featured_image_path) : asset('shopus/assets/images/homepage-one/blog-2.webp') }}" alt="{{ $featuredPost->featured_image_alt ?? $featuredPost->title }}">
                                        <div class="position-absolute bottom-0 start-0 end-0 p-4" style="background: linear-gradient(180deg, rgba(9, 17, 32, 0) 0%, rgba(9, 17, 32, 0.72) 100%);">
                                            <span class="badge bg-white text-dark mb-3">{{ $featuredPost->category->name ?? 'Editorial' }}</span>
                                            <h3 class="h4 text-white mb-2">{{ $featuredPost->title }}</h3>
                                            <p class="text-white-50 mb-0">{{ \Illuminate\Support\Str::limit($featuredPost->excerpt, 120) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="blog-filter-bar mb-5">
                <form method="GET" class="row g-3">
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label text-secondary">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search by keyword" value="{{ $searchTerm }}">
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <label class="form-label text-secondary">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" @selected($selectedCategory === $category->slug)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <label class="form-label text-secondary">Tag</label>
                        <input type="text" name="tag" class="form-control" placeholder="e.g. summer-style" value="{{ $selectedTag }}">
                    </div>
                    <div class="col-lg-2 d-flex align-items-end">
                        <div class="d-flex w-100 gap-2">
                            <button class="btn btn-primary flex-grow-1 radius-12">Apply</button>
                            <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary radius-12">Reset</a>
                        </div>
                    </div>
                </form>
            </div>

            <div id="blog-articles">
                @if($posts->isEmpty())
                    <div class="blog-no-results">
                        <img src="{{ asset('shopus/assets/images/homepage-one/empty-wishlist.webp') }}" alt="No posts" class="mb-4" style="max-width: 220px;">
                        <h3 class="h4 fw-semibold mb-2">No posts found</h3>
                        <p class="text-secondary mb-3">Try adjusting your filters or check back soon for fresh editorials.</p>
                        <a href="{{ route('blog.index') }}" class="btn btn-primary radius-12">Back to Blog</a>
                    </div>
                @else
                    <div class="row g-4">
                        @foreach($posts as $post)
                            <div class="col-md-6 col-xl-4 d-flex">
                                <article class="blog-card w-100">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none">
                                        <img src="{{ $post->featured_image_path ? asset('storage/'.$post->featured_image_path) : asset('shopus/assets/images/homepage-one/blog-1.webp') }}" alt="{{ $post->featured_image_alt ?? $post->title }}">
                                    </a>
                                    <div class="blog-card-body">
                                        @if($post->category)
                                            <span class="badge">{{ $post->category->name }}</span>
                                        @endif
                                        <a href="{{ route('blog.show', $post->slug) }}" class="h5 text-dark fw-semibold text-decoration-none">{{ $post->title }}</a>
                                        <p class="text-secondary mb-0">{{ \Illuminate\Support\Str::limit($post->excerpt ?? strip_tags($post->content), 110) }}</p>
                                    </div>
                                    <div class="blog-card-footer">
                                        <span>{{ optional($post->published_at)->diffForHumans() }}</span>
                                        @if($post->author)
                                            <span>By {{ $post->author->name }}</span>
                                        @endif
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-5">
                        {{ $posts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

