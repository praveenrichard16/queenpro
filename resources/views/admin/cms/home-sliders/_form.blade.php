<input type="hidden" name="active_tab" value="{{ request('active_tab', 'hero') }}">
<div class="row g-4">
    <div class="col-lg-6">
        <label class="form-label fw-semibold text-secondary-light">Title</label>
        <input type="text" name="title" value="{{ old('title', $slider->title) }}" class="form-control" placeholder="Headline for this slide">
        @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-lg-6">
        <label class="form-label fw-semibold text-secondary-light">Alt Text</label>
        <input type="text" name="alt_text" value="{{ old('alt_text', $slider->alt_text) }}" class="form-control" placeholder="Describe the slide image for accessibility">
        @error('alt_text') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold text-secondary-light">Description</label>
        <textarea name="description" rows="3" class="form-control" placeholder="Optional supporting copy shown on top of the slide">{{ old('description', $slider->description) }}</textarea>
        @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-lg-6">
        <label class="form-label fw-semibold text-secondary-light">Desktop Image <span class="text-danger">*</span></label>
        <input type="file" name="desktop_image" id="desktop_image_input" class="form-control" accept=".jpg,.jpeg,.png,.webp">
        <div class="form-text">Upload 1600×900 JPG/PNG/WebP up to 4&nbsp;MB.</div>
        @error('desktop_image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

        <div id="desktop_image_preview_container" class="mt-3" style="display:none;">
            <span class="d-block text-secondary-light small mb-2">Preview:</span>
            <img id="desktop_image_preview" src="" alt="Desktop image preview" class="img-fluid rounded border">
        </div>

        @if($slider->desktop_image_path)
            <div class="mt-3" id="current_desktop_image">
                <span class="d-block text-secondary-light small mb-2">Current desktop image:</span>
                <img src="{{ asset('storage/' . $slider->desktop_image_path) }}" alt="{{ $slider->alt_text ?? $slider->title ?? 'Slide preview' }}" class="img-fluid rounded border" onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div class="alert alert-warning small mb-0" style="display:none;">Image not found. Please re-upload the desktop image.</div>
            </div>
        @endif
    </div>

    <div class="col-lg-6">
        <label class="form-label fw-semibold text-secondary-light">Mobile Image <span class="text-danger">*</span></label>
        <input type="file" name="mobile_image" id="mobile_image_input" class="form-control" accept=".jpg,.jpeg,.png,.webp">
        <div class="form-text">Upload 800×1300 JPG/PNG/WebP up to 4&nbsp;MB.</div>
        @error('mobile_image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

        <div id="mobile_image_preview_container" class="mt-3" style="display:none;">
            <span class="d-block text-secondary-light small mb-2">Preview:</span>
            <img id="mobile_image_preview" src="" alt="Mobile image preview" class="img-fluid rounded border" style="max-height: 260px; object-fit: cover;">
        </div>

        @if($slider->mobile_image_path)
            <div class="mt-3" id="current_mobile_image">
                <span class="d-block text-secondary-light small mb-2">Current mobile image:</span>
                <img src="{{ asset('storage/' . $slider->mobile_image_path) }}" alt="{{ $slider->alt_text ?? $slider->title ?? 'Slide mobile preview' }}" class="img-fluid rounded border" style="max-height: 260px; object-fit: cover;" onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div class="alert alert-warning small mb-0" style="display:none;">Image not found. Please re-upload the mobile image.</div>
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        <label class="form-label fw-semibold text-secondary-light">Button Text</label>
        <input type="text" name="button_text" value="{{ old('button_text', $slider->button_text) }}" class="form-control" placeholder="e.g. Shop Now">
        @error('button_text') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-lg-4">
        <label class="form-label fw-semibold text-secondary-light">Button Link</label>
        <input type="url" name="button_link" value="{{ old('button_link', $slider->button_link) }}" class="form-control" placeholder="https://example.com/collection">
        <div class="form-text">If button is hidden, clicking the slide will redirect to this URL</div>
        @error('button_link') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-lg-2">
        <label class="form-label fw-semibold text-secondary-light">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $slider->sort_order ?? 1) }}" min="1" max="50" class="form-control">
        @error('sort_order') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-lg-2 d-flex align-items-end">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" id="home-slider-active" value="1" {{ old('is_active', $slider->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="home-slider-active">Active</label>
        </div>
    </div>
    
    <input type="hidden" name="show_title" value="0">
    <input type="hidden" name="show_description" value="0">
    <input type="hidden" name="show_button" value="0">

    <div class="col-12">
        <hr class="my-3">
        <h5 class="fw-semibold mb-3">Display Options</h5>
    </div>

    <div class="col-lg-4">
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="show_title" id="show_title" value="1" {{ old('show_title', $slider->show_title ?? true) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="show_title">Show Title</label>
        </div>
        @error('show_title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-lg-4">
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="show_description" id="show_description" value="1" {{ old('show_description', $slider->show_description ?? true) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="show_description">Show Description</label>
        </div>
        @error('show_description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-lg-4">
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="show_button" id="show_button" value="1" {{ old('show_button', $slider->show_button ?? true) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="show_button">Show Button</label>
        </div>
        @error('show_button') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-lg-4">
        <label class="form-label fw-semibold text-secondary-light">Title Position</label>
        <select name="title_position" class="form-select">
            <option value="">Default (Left)</option>
            <option value="left" {{ old('title_position', $slider->title_position) === 'left' ? 'selected' : '' }}>Left</option>
            <option value="center" {{ old('title_position', $slider->title_position) === 'center' ? 'selected' : '' }}>Center</option>
            <option value="right" {{ old('title_position', $slider->title_position) === 'right' ? 'selected' : '' }}>Right</option>
        </select>
        @error('title_position') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-lg-4">
        <label class="form-label fw-semibold text-secondary-light">Description Position</label>
        <select name="description_position" class="form-select">
            <option value="">Default (Left)</option>
            <option value="left" {{ old('description_position', $slider->description_position) === 'left' ? 'selected' : '' }}>Left</option>
            <option value="center" {{ old('description_position', $slider->description_position) === 'center' ? 'selected' : '' }}>Center</option>
            <option value="right" {{ old('description_position', $slider->description_position) === 'right' ? 'selected' : '' }}>Right</option>
        </select>
        @error('description_position') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-lg-4">
        <label class="form-label fw-semibold text-secondary-light">Button Position</label>
        <select name="button_position" class="form-select">
            <option value="">Default (Left)</option>
            <option value="left" {{ old('button_position', $slider->button_position) === 'left' ? 'selected' : '' }}>Left</option>
            <option value="center" {{ old('button_position', $slider->button_position) === 'center' ? 'selected' : '' }}>Center</option>
            <option value="right" {{ old('button_position', $slider->button_position) === 'right' ? 'selected' : '' }}>Right</option>
        </select>
        @error('button_position') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-lg-4">
        <label class="form-label fw-semibold text-secondary-light">Button Size</label>
        <select name="button_size" class="form-select">
            <option value="">Default</option>
            <option value="small" {{ old('button_size', $slider->button_size) === 'small' ? 'selected' : '' }}>Small</option>
            <option value="medium" {{ old('button_size', $slider->button_size) === 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="large" {{ old('button_size', $slider->button_size) === 'large' ? 'selected' : '' }}>Large</option>
        </select>
        @error('button_size') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-lg-4">
        <label class="form-label fw-semibold text-secondary-light">Button Color</label>
        <div class="input-group">
            <input type="text" name="button_color" id="button_color" value="{{ old('button_color', $slider->button_color) }}" class="form-control" placeholder="#111827">
            <input type="color" id="button_color_picker" class="form-control form-control-color" style="width: 60px;" value="{{ old('button_color', $slider->button_color ?: '#111827') }}">
        </div>
        <div class="form-text">Enter hex color for button background</div>
        @error('button_color') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mt-4 d-flex justify-content-end gap-3">
    <a href="{{ route('admin.cms.home.index') }}?active_tab=hero" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">
        {{ $submitLabel }}
    </button>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Desktop image preview
        const desktopInput = document.getElementById('desktop_image_input');
        const desktopPreview = document.getElementById('desktop_image_preview');
        const desktopPreviewContainer = document.getElementById('desktop_image_preview_container');
        const currentDesktopImage = document.getElementById('current_desktop_image');

        if (desktopInput) {
            desktopInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        desktopPreview.src = e.target.result;
                        desktopPreviewContainer.style.display = 'block';
                        if (currentDesktopImage) {
                            currentDesktopImage.style.display = 'none';
                        }
                    };
                    reader.readAsDataURL(file);
                } else {
                    desktopPreviewContainer.style.display = 'none';
                    if (currentDesktopImage) {
                        currentDesktopImage.style.display = 'block';
                    }
                }
            });
        }

        // Mobile image preview
        const mobileInput = document.getElementById('mobile_image_input');
        const mobilePreview = document.getElementById('mobile_image_preview');
        const mobilePreviewContainer = document.getElementById('mobile_image_preview_container');
        const currentMobileImage = document.getElementById('current_mobile_image');

        if (mobileInput) {
            mobileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        mobilePreview.src = e.target.result;
                        mobilePreviewContainer.style.display = 'block';
                        if (currentMobileImage) {
                            currentMobileImage.style.display = 'none';
                        }
                    };
                    reader.readAsDataURL(file);
                } else {
                    mobilePreviewContainer.style.display = 'none';
                    if (currentMobileImage) {
                        currentMobileImage.style.display = 'block';
                    }
                }
            });
        }

        // Button color picker sync
        const buttonColorInput = document.getElementById('button_color');
        const buttonColorPicker = document.getElementById('button_color_picker');
        
        if (buttonColorPicker && buttonColorInput) {
            buttonColorPicker.addEventListener('input', function() {
                buttonColorInput.value = this.value;
            });
            
            buttonColorInput.addEventListener('input', function() {
                if (this.value.match(/^#[0-9A-Fa-f]{6}$/)) {
                    buttonColorPicker.value = this.value;
                }
            });
        }
    });
</script>
@endpush

