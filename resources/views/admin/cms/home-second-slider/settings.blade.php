@extends('layouts.admin')

@section('title', '2nd Section Slider')

@section('content')
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <div>
                <h6 class="fw-semibold mb-1">2nd Section Slider</h6>
                <p class="text-secondary-light mb-0">Upload the secondary promotional banner that appears after featured products on the storefront.</p>
            </div>
            <a href="{{ route('admin.cms.home.sliders.index') }}" class="btn btn-outline-secondary radius-12">
                <iconify-icon icon="solar:alt-arrow-left-linear" class="me-1"></iconify-icon>
                Back to home sliders
            </a>
        </div>

        <div class="card border-0">
            <div class="card-body p-24">
                <form method="POST"
                      action="{{ route('admin.cms.home.second-slider.update') }}"
                      enctype="multipart/form-data"
                      class="row g-4">
                    @csrf

                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input type="checkbox"
                                   class="form-check-input"
                                   name="is_active"
                                   id="second-slider-active"
                                   value="1"
                                   {{ old('is_active', $secondSlider['is_active']) ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium" for="second-slider-active">
                                Display 2nd section slider on the homepage
                            </label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary-light">Desktop image (min 1200×500px)</label>
                        <input type="file"
                               name="desktop_image"
                               class="form-control bg-neutral-50 radius-12 h-56-px @error('desktop_image') is-invalid @enderror">
                        @error('desktop_image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        <div class="form-text mt-1">Upload JPG/PNG/WebP up to 4MB. This image is shown on laptop & desktop screens.</div>
                        @if($secondSlider['desktop_image_path'])
                            <div class="mt-3">
                                <div class="text-secondary-light mb-2">Current desktop preview</div>
                                <img src="{{ asset('storage/' . $secondSlider['desktop_image_path']) }}"
                                     alt="Current desktop visual"
                                     class="img-fluid rounded-3 border"
                                     style="max-height:240px;object-fit:cover;">
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary-light">Mobile image (min 750×1000px)</label>
                        <input type="file"
                               name="mobile_image"
                               class="form-control bg-neutral-50 radius-12 h-56-px @error('mobile_image') is-invalid @enderror">
                        @error('mobile_image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        <div class="form-text mt-1">Upload JPG/PNG/WebP up to 4MB that fits portrait screens.</div>
                        @if($secondSlider['mobile_image_path'])
                            <div class="mt-3">
                                <div class="text-secondary-light mb-2">Current mobile preview</div>
                                <img src="{{ asset('storage/' . $secondSlider['mobile_image_path']) }}"
                                     alt="Current mobile visual"
                                     class="img-fluid rounded-3 border"
                                     style="max-height:240px;object-fit:cover;">
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary-light">Alt text</label>
                        <input type="text"
                               name="alt_text"
                               class="form-control bg-neutral-50 radius-12 h-56-px @error('alt_text') is-invalid @enderror"
                               value="{{ old('alt_text', $secondSlider['alt_text']) }}"
                               placeholder="Describe the visual for accessibility">
                        @error('alt_text') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary-light">Optional link</label>
                        <input type="url"
                               name="button_link"
                               class="form-control bg-neutral-50 radius-12 h-56-px @error('button_link') is-invalid @enderror"
                               value="{{ old('button_link', $secondSlider['button_link']) }}"
                               placeholder="https://example.com/collection">
                        @error('button_link') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        <div class="form-text mt-1">Provide a URL to make the slider clickable.</div>
                    </div>

                    <div class="col-12 d-flex gap-3 mt-3">
                        <button type="submit" class="btn btn-primary radius-12 px-24">
                            <iconify-icon icon="solar:floppy-disk-line-duotone" class="me-1"></iconify-icon>
                            Save slider
                        </button>
                        <a href="{{ route('admin.cms.home.sliders.index') }}" class="btn btn-outline-secondary radius-12 px-24">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

