@extends('layouts.app')

@section('title', 'Social Hub')

@section('content')
    <x-page-hero
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Social Hub']
        ]"
        eyebrow="Connect with us"
        title="Join the Social Hub"
        description="Follow our latest stories, styling reels, and community highlights across every platform."
    />

    <section class="page-shell pt-0">
        <div class="container px-4 px-lg-5">
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="page-card p-4 h-100">
                        <span class="badge-soft mb-3">Instagram</span>
                        <h3 class="h5 fw-semibold mb-2">Daily inspiration</h3>
                        <p class="text-soft mb-3">Behind-the-scenes styling, capsule edits, and live drops directly from our creative studio.</p>
                        <a href="#" class="btn btn-primary w-100" target="_blank" rel="noopener">Follow @wowdash</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="page-card p-4 h-100">
                        <span class="badge-soft mb-3">TikTok</span>
                        <h3 class="h5 fw-semibold mb-2">Quick styling reels</h3>
                        <p class="text-soft mb-3">Get ready with us, discover quick styling hacks, and explore influencer collaborations.</p>
                        <a href="#" class="btn btn-primary w-100" target="_blank" rel="noopener">Watch on TikTok</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="page-card p-4 h-100">
                        <span class="badge-soft mb-3">YouTube</span>
                        <h3 class="h5 fw-semibold mb-2">Long-form stories</h3>
                        <p class="text-soft mb-3">Catch interviews, lookbooks, and long-form stories from designers throughout the region.</p>
                        <a href="#" class="btn btn-primary w-100" target="_blank" rel="noopener">Subscribe on YouTube</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

