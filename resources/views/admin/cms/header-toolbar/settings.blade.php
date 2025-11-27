@extends('layouts.admin')

@section('title', 'Toolbar Settings')

@section('content')
    <div class="row g-4">
        <div class="col-12">
            <div class="card radius-16 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h1 class="h3 mb-0">Toolbar Settings</h1>
                            <p class="text-muted mb-0">Configure global settings for the header toolbar.</p>
                        </div>
                        <a href="{{ route('admin.cms.header.toolbar.index') }}" class="btn btn-outline-secondary">
                            <iconify-icon icon="solar:arrow-left-linear" class="me-1"></iconify-icon>
                            Back to Items
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.cms.header.toolbar.settings.update') }}" method="POST" class="mt-4">
                        @csrf
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <label class="form-label fw-semibold text-secondary-light">Toolbar Height</label>
                                @php
                                    $currentHeight = old('toolbar_height', $settings['toolbar_height'] ?? '');
                                    $isPresetValue = in_array($currentHeight, ['small', 'medium', 'large']);
                                    $customHeightValue = (!$isPresetValue && !empty($currentHeight)) ? $currentHeight : '';
                                @endphp
                                <select name="toolbar_height" id="toolbar_height" class="form-select">
                                    <option value="" {{ !$isPresetValue ? 'selected' : '' }}>Default</option>
                                    <option value="small" {{ $currentHeight === 'small' ? 'selected' : '' }}>Small</option>
                                    <option value="medium" {{ $currentHeight === 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="large" {{ $currentHeight === 'large' ? 'selected' : '' }}>Large</option>
                                </select>
                                <div class="form-text">Or enter custom value (e.g., 40px)</div>
                                <input type="text" id="toolbar_height_custom" value="{{ old('toolbar_height', $customHeightValue) }}" class="form-control mt-2" placeholder="Custom height (e.g., 50px)">
                                @error('toolbar_height') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-lg-3">
                                <label class="form-label fw-semibold text-secondary-light">Font Size</label>
                                @php
                                    $currentFontSize = old('toolbar_font_size', $settings['toolbar_font_size'] ?? '');
                                    $isPresetFontSize = in_array($currentFontSize, ['small', 'medium', 'large']);
                                    $customFontSizeValue = (!$isPresetFontSize && !empty($currentFontSize)) ? $currentFontSize : '';
                                @endphp
                                <select name="toolbar_font_size" id="toolbar_font_size" class="form-select">
                                    <option value="" {{ !$isPresetFontSize ? 'selected' : '' }}>Default</option>
                                    <option value="small" {{ $currentFontSize === 'small' ? 'selected' : '' }}>Small</option>
                                    <option value="medium" {{ $currentFontSize === 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="large" {{ $currentFontSize === 'large' ? 'selected' : '' }}>Large</option>
                                </select>
                                <div class="form-text">Or enter custom value (e.g., 14px)</div>
                                <input type="text" id="toolbar_font_size_custom" value="{{ old('toolbar_font_size', $customFontSizeValue) }}" class="form-control mt-2" placeholder="Custom size (e.g., 16px)">
                                @error('toolbar_font_size') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-lg-3">
                                <label class="form-label fw-semibold text-secondary-light">Background Color</label>
                                <div class="input-group">
                                    <input type="text" name="toolbar_background_color" id="toolbar_bg_color" value="{{ old('toolbar_background_color', $settings['toolbar_background_color']) }}" class="form-control" placeholder="#f97316 or gradient">
                                    <input type="color" id="toolbar_bg_color_picker" class="form-control form-control-color" style="width: 60px;" value="{{ old('toolbar_background_color', $settings['toolbar_background_color'] && $settings['toolbar_background_color'] !== 'gradient' ? $settings['toolbar_background_color'] : '#f97316') }}">
                                </div>
                                <div class="form-text">Enter hex color (e.g., #f97316) or "gradient" for default gradient</div>
                                @error('toolbar_background_color') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-lg-12">
                                <label class="form-label fw-semibold text-secondary-light">Toolbar Mode</label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="toolbar_mode" id="toolbar_mode_scrolling" value="scrolling" {{ old('toolbar_mode', $settings['toolbar_mode']) === 'scrolling' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="toolbar_mode_scrolling">
                                            Scrolling
                                            <small class="d-block text-muted">Items scroll horizontally with animation</small>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="toolbar_mode" id="toolbar_mode_fixed" value="fixed" {{ old('toolbar_mode', $settings['toolbar_mode']) === 'fixed' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="toolbar_mode_fixed">
                                            Fixed
                                            <small class="d-block text-muted">Items displayed in a static row (no animation)</small>
                                        </label>
                                    </div>
                                </div>
                                @error('toolbar_mode') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <div class="card border mt-3">
                                    <div class="card-body">
                                        <label class="form-label fw-semibold text-secondary-light mb-3">Live Preview</label>
                                        <div id="toolbar_preview" class="scrolling-toolbar-preview p-3 rounded" style="background: {{ $settings['toolbar_background_color'] && $settings['toolbar_background_color'] !== 'gradient' ? $settings['toolbar_background_color'] : 'linear-gradient(135deg, #f97316 0%, #ff6b35 100%)' }}; color: #ffffff; min-height: 60px; display: flex; align-items: center; justify-content: center;">
                                            <div class="d-flex align-items-center gap-2">
                                                <span>🎉</span>
                                                <span id="preview_text">Sample toolbar item preview</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-3">
                            <a href="{{ route('admin.cms.header.toolbar.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                Save Settings
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
        const toolbarHeight = document.getElementById('toolbar_height');
        const toolbarHeightCustom = document.getElementById('toolbar_height_custom');
        const toolbarFontSize = document.getElementById('toolbar_font_size');
        const toolbarFontSizeCustom = document.getElementById('toolbar_font_size_custom');
        const bgColorInput = document.getElementById('toolbar_bg_color');
        const bgColorPicker = document.getElementById('toolbar_bg_color_picker');
        const preview = document.getElementById('toolbar_preview');
        const previewText = document.getElementById('preview_text');
        const toolbarModeScrolling = document.getElementById('toolbar_mode_scrolling');
        const toolbarModeFixed = document.getElementById('toolbar_mode_fixed');

        function updatePreview() {
            let bgColor = bgColorInput.value || 'gradient';
            let height = toolbarHeight.value || toolbarHeightCustom.value || '';
            let heightValue = '';
            
            if (height === 'small') heightValue = '0.4rem';
            else if (height === 'medium') heightValue = '0.5rem';
            else if (height === 'large') heightValue = '0.7rem';
            else if (height) heightValue = height;

            // Get font size
            let fontSize = toolbarFontSize.value || toolbarFontSizeCustom.value || '';
            let fontSizeValue = '';
            if (fontSize === 'small') fontSizeValue = '0.75rem';
            else if (fontSize === 'medium') fontSizeValue = '0.875rem';
            else if (fontSize === 'large') fontSizeValue = '1rem';
            else if (fontSize) fontSizeValue = fontSize;

            // Update background
            if (bgColor === 'gradient' || !bgColor) {
                preview.style.background = 'linear-gradient(135deg, #f97316 0%, #ff6b35 100%)';
            } else {
                preview.style.background = bgColor;
            }

            // Update height
            if (heightValue) {
                preview.style.padding = heightValue + ' 0';
            } else {
                preview.style.padding = '0.5rem 0';
            }

            // Update font size
            if (fontSizeValue) {
                previewText.style.fontSize = fontSizeValue;
            } else {
                previewText.style.fontSize = '';
            }
        }

        // Handle custom height input
        toolbarHeight.addEventListener('change', function() {
            if (this.value) {
                toolbarHeightCustom.value = '';
            }
            updatePreview();
        });

        toolbarHeightCustom.addEventListener('input', function() {
            if (this.value) {
                toolbarHeight.value = '';
            }
            updatePreview();
        });

        // Handle custom font size input
        toolbarFontSize.addEventListener('change', function() {
            if (this.value) {
                toolbarFontSizeCustom.value = '';
            }
            updatePreview();
        });

        toolbarFontSizeCustom.addEventListener('input', function() {
            if (this.value) {
                toolbarFontSize.value = '';
            }
            updatePreview();
        });

        // Sync color picker with text input
        bgColorPicker.addEventListener('input', function() {
            bgColorInput.value = this.value;
            updatePreview();
        });

        bgColorInput.addEventListener('input', function() {
            if (this.value.match(/^#[0-9A-Fa-f]{6}$/)) {
                bgColorPicker.value = this.value;
            }
            updatePreview();
        });

        // Sync custom values to hidden inputs before form submit
        document.querySelector('form').addEventListener('submit', function(e) {
            // Handle toolbar height: if custom value exists, use it and clear select
            if (toolbarHeightCustom.value && toolbarHeightCustom.value.trim() !== '') {
                // Clear the select dropdown
                toolbarHeight.value = '';
                // Remove any existing hidden input with same name
                const existingHeightInput = this.querySelector('input[name="toolbar_height"][type="hidden"]');
                if (existingHeightInput) {
                    existingHeightInput.remove();
                }
                // Create new hidden input with custom value
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'toolbar_height';
                hiddenInput.value = toolbarHeightCustom.value.trim();
                this.appendChild(hiddenInput);
            } else if (toolbarHeight.value) {
                // If select has value, ensure custom is cleared
                toolbarHeightCustom.value = '';
            }
            
            // Handle toolbar font size: if custom value exists, use it and clear select
            if (toolbarFontSizeCustom.value && toolbarFontSizeCustom.value.trim() !== '') {
                // Clear the select dropdown
                toolbarFontSize.value = '';
                // Remove any existing hidden input with same name
                const existingFontSizeInput = this.querySelector('input[name="toolbar_font_size"][type="hidden"]');
                if (existingFontSizeInput) {
                    existingFontSizeInput.remove();
                }
                // Create new hidden input with custom value
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'toolbar_font_size';
                hiddenInput.value = toolbarFontSizeCustom.value.trim();
                this.appendChild(hiddenInput);
            } else if (toolbarFontSize.value) {
                // If select has value, ensure custom is cleared
                toolbarFontSizeCustom.value = '';
            }
        });

        // Initial preview update
        updatePreview();
    });
</script>
@endpush
@endsection

