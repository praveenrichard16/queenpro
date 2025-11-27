@extends('layouts.admin')

@section('title', 'Home Page SEO Settings')

@section('content')
    <div class="row g-4">
        <div class="col-12">
            <div class="card radius-16 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h1 class="h3 mb-0">Home Page SEO Settings</h1>
                            <p class="text-muted mb-0">Configure meta tags for the home page to improve search engine visibility.</p>
                        </div>
                        <a href="{{ route('admin.cms.home.sliders.index') }}" class="btn btn-outline-secondary">
                            <iconify-icon icon="solar:arrow-left-linear" class="me-1"></iconify-icon>
                            Back to Sliders
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.cms.home.seo.settings.update') }}" method="POST" class="mt-4">
                        @csrf
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label fw-semibold text-secondary-light">Meta Title</label>
                                <input type="text" name="home_meta_title" id="home_meta_title" value="{{ old('home_meta_title', $settings['home_meta_title']) }}" class="form-control" placeholder="Enter meta title for home page" maxlength="255">
                                <div class="form-text">Recommended: 50-60 characters. This appears in search engine results and browser tabs.</div>
                                <div class="mt-2">
                                    <small class="text-muted">Character count: <span id="title_count">0</span>/255</small>
                                </div>
                                @error('home_meta_title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold text-secondary-light">Meta Description</label>
                                <textarea name="home_meta_description" id="home_meta_description" rows="4" class="form-control" placeholder="Enter meta description for home page" maxlength="500">{{ old('home_meta_description', $settings['home_meta_description']) }}</textarea>
                                <div class="form-text">Recommended: 150-160 characters. This appears in search engine results below the title.</div>
                                <div class="mt-2">
                                    <small class="text-muted">Character count: <span id="description_count">0</span>/500</small>
                                </div>
                                @error('home_meta_description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold text-secondary-light">Meta Keywords</label>
                                <input type="text" name="home_meta_keywords" id="home_meta_keywords" value="{{ old('home_meta_keywords', $settings['home_meta_keywords']) }}" class="form-control" placeholder="Enter keywords separated by commas (e.g., ecommerce, online shop, products)" maxlength="500">
                                <div class="form-text">Enter relevant keywords separated by commas. While less important for modern SEO, some search engines still use this.</div>
                                <div class="mt-2">
                                    <small class="text-muted">Character count: <span id="keywords_count">0</span>/500</small>
                                </div>
                                @error('home_meta_keywords') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <div class="card border bg-light mt-3">
                                    <div class="card-body">
                                        <h6 class="fw-semibold mb-3">Preview</h6>
                                        <div class="border-start border-3 border-primary ps-3">
                                            <div class="text-primary mb-1" style="font-size: 1.1rem; font-weight: 500;" id="preview_title">
                                                {{ $settings['home_meta_title'] ?: 'Your Meta Title Will Appear Here' }}
                                            </div>
                                            <div class="text-success small mb-1" id="preview_url">
                                                {{ url('/') }}
                                            </div>
                                            <div class="text-muted small" id="preview_description">
                                                {{ $settings['home_meta_description'] ?: 'Your meta description will appear here. This is how your page will look in search engine results.' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-3">
                            <a href="{{ route('admin.cms.home.sliders.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                Save SEO Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('home_meta_title');
        const descriptionInput = document.getElementById('home_meta_description');
        const keywordsInput = document.getElementById('home_meta_keywords');
        const titleCount = document.getElementById('title_count');
        const descriptionCount = document.getElementById('description_count');
        const keywordsCount = document.getElementById('keywords_count');
        const previewTitle = document.getElementById('preview_title');
        const previewDescription = document.getElementById('preview_description');

        function updateCounts() {
            titleCount.textContent = titleInput.value.length;
            descriptionCount.textContent = descriptionInput.value.length;
            keywordsCount.textContent = keywordsInput.value.length;
        }

        function updatePreview() {
            previewTitle.textContent = titleInput.value || 'Your Meta Title Will Appear Here';
            previewDescription.textContent = descriptionInput.value || 'Your meta description will appear here. This is how your page will look in search engine results.';
        }

        titleInput.addEventListener('input', function() {
            updateCounts();
            updatePreview();
        });

        descriptionInput.addEventListener('input', function() {
            updateCounts();
            updatePreview();
        });

        keywordsInput.addEventListener('input', function() {
            updateCounts();
        });

        // Initial counts
        updateCounts();
    });
</script>
@endpush
@endsection

