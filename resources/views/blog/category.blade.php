@extends('layouts.app')

@section('title', $category->name . ' Articles')

@section('meta')
    <title>{{ $category->meta_title ?: $category->name }} &mdash; {{ $appSettings['site_name'] ?? config('app.name') }}</title>
    @if($category->meta_description)
        <meta name="description" content="{{ $category->meta_description }}">
    @endif
@endsection

@push('styles')
    <style>
        .blog-category-hero {
            border-radius: 24px;
            background: linear-gradient(92deg, #f6edff 4%, #f0fbff 54%, #ffeef8 94%);
            padding: clamp(2.25rem, 4vw, 3.75rem);
        }
        .blog-card {
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 18px 50px -25px rgba(30, 41, 59, 0.2);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .blog-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 26px 70px -28px rgba(30, 41, 59, 0.3);
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
            gap: 1rem;
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
        .blog-empty {
            border-radius: 18px;
            background: #ffffff;
            padding: 3rem;
            text-align: center;
            box-shadow: inset 0 0 0 1px rgba(2, 69, 80, 0.08);
        }
    </style>
@endpush

@section('content')
    <section class="py-5 py-lg-6">
        <div class="container">
            <div class="blog-category-hero mb-5">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <a href="{{ route('blog.index') }}" class="text-secondary text-decoration-none d-inline-flex align-items-center gap-2 mb-3">
                            <iconify-icon icon="solar:arrow-left-linear"></iconify-icon>
                            Back to Blog
                        </a>
                        <h1 class="display-5 fw-semibold text-dark mb-3">{{ $category->name }}</h1>
                        <p class="lead text-secondary mb-0">{{ $category->description ?: 'Curated stories and guides for this category.' }}</p>
                    </div>
                    @if($featuredPost)
                        <div class="col-lg-5">
                            <a href="{{ route('blog.show', $featuredPost->slug) }}" class="text-decoration-none">
                                <div class="position-relative overflow-hidden rounded-4 shadow-sm">
                                    <img src="{{ $featuredPost->featured_image_path ? asset('storage/'.$featuredPost->featured_image_path) : asset('shopus/assets/images/homepage-one/blog-3.webp') }}" class="img-fluid" alt="{{ $featuredPost->featured_image_alt ?? $featuredPost->title }}" style="object-fit: cover; height: 260px; width: 100%;">
                                    <div class="position-absolute bottom-0 start-0 end-0 p-4" style="background: linear-gradient(180deg, rgba(9, 17, 32, 0) 0%, rgba(9, 17, 32, 0.72) 100%);">
                                        <span class="badge bg-white text-dark mb-2">Featured</span>
                                        <h3 class="h5 text-white mb-0">{{ $featuredPost->title }}</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            @if($posts->isEmpty())
                <div class="blog-empty">
                    <img src="{{ asset('shopus/assets/images/homepage-one/empty-wishlist.webp') }}" alt="No posts" class="mb-4" style="max-width: 220px;">
                    <h3 class="h4 fw-semibold mb-2">No articles yet</h3>
                    <p class="text-secondary mb-3">We are crafting stories for this category. Check back soon!</p>
                    <a href="{{ route('blog.index') }}" class="btn btn-primary radius-12">Explore other categories</a>
                </div>
            @else
                <div class="row g-4">
                    @foreach($posts as $post)
                        <div class="col-md-6 col-xl-4 d-flex">
                            <article class="blog-card w-100">
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none">
                                    <img src="{{ $post->featured_image_path ? asset('storage/'.$post->featured_image_path) : asset('shopus/assets/images/homepage-one/blog-2.webp') }}" alt="{{ $post->featured_image_alt ?? $post->title }}">
                                </a>
                                <div class="blog-card-body">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="h5 text-dark fw-semibold text-decoration-none">{{ $post->title }}</a>
                                    <p class="text-secondary mb-0">{{ \Illuminate\Support\Str::limit($post->excerpt ?? strip_tags($post->content), 110) }}</p>
                                </div>
                                <div class="blog-card-footer">
                                    <span>{{ optional($post->published_at)->format('M d, Y') }}</span>
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
    </section>
@endsection

