<div class="row g-4">
    <div class="col-lg-8">
        <label class="form-label fw-semibold text-secondary-light">Text <span class="text-danger">*</span></label>
        <input type="text" name="text" id="toolbar_text" value="{{ old('text', $item->text) }}" class="form-control" placeholder="e.g. Free Shipping on Orders Over {{ \App\Services\CurrencyService::symbol() }}100" required>
        @error('text') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-lg-4">
        <label class="form-label fw-semibold text-secondary-light">Icon (Emoji)</label>
        <input type="text" name="icon" id="toolbar_icon" value="{{ old('icon', $item->icon) }}" class="form-control" placeholder="🎉" maxlength="10">
        <div class="form-text">Enter an emoji or leave empty</div>
        @error('icon') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-lg-6">
        <label class="form-label fw-semibold text-secondary-light">Text Color</label>
        <div class="input-group">
            <input type="text" name="text_color" id="toolbar_text_color" value="{{ old('text_color', $item->text_color) }}" class="form-control" placeholder="#ffffff">
            <input type="color" id="toolbar_text_color_picker" class="form-control form-control-color" style="width: 60px;" value="{{ old('text_color', $item->text_color ?: '#ffffff') }}">
        </div>
        <div class="form-text">Enter hex color (e.g., #ffffff) for text color</div>
        @error('text_color') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-lg-12">
        <label class="form-label fw-semibold text-secondary-light">Link (Optional)</label>
        <input type="url" name="link" id="toolbar_link" value="{{ old('link', $item->link) }}" class="form-control" placeholder="https://example.com">
        <div class="form-text">Add a URL to make the toolbar item clickable</div>
        @error('link') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-lg-6">
        <label class="form-label fw-semibold text-secondary-light">Font Size</label>
        <select name="font_size" id="toolbar_font_size" class="form-select">
            <option value="">Default</option>
            <option value="small" {{ old('font_size', $item->font_size) === 'small' ? 'selected' : '' }}>Small</option>
            <option value="medium" {{ old('font_size', $item->font_size) === 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="large" {{ old('font_size', $item->font_size) === 'large' ? 'selected' : '' }}>Large</option>
        </select>
        <div class="form-text">Or enter custom value (e.g., 16px)</div>
        <input type="text" id="toolbar_font_size_custom" value="{{ old('font_size', $item->font_size && !in_array($item->font_size, ['small', 'medium', 'large']) ? $item->font_size : '') }}" class="form-control mt-2" placeholder="Custom size (e.g., 18px)">
        @error('font_size') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-lg-6">
        <label class="form-label fw-semibold text-secondary-light">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}" min="0" max="999" class="form-control">
        @error('sort_order') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-lg-12 d-flex align-items-end">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" id="toolbar_active" value="1" {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="toolbar_active">Active</label>
        </div>
    </div>

    <div class="col-12">
        <div class="card border mt-3">
            <div class="card-body">
                <label class="form-label fw-semibold text-secondary-light mb-3">Live Preview</label>
                @php
                    $globalBgColor = \App\Models\Setting::getValue('toolbar_background_color', 'gradient');
                    $previewBgColor = $globalBgColor && $globalBgColor !== 'gradient' ? $globalBgColor : 'linear-gradient(135deg, #f97316 0%, #ff6b35 100%)';
                @endphp
                <div id="toolbar_preview" class="scrolling-toolbar-preview p-3 rounded" style="background: {{ $previewBgColor }}; color: {{ $item->text_color ?: '#ffffff' }}; min-height: 60px; display: flex; align-items: center; justify-content: center;">
                    <div class="d-flex align-items-center gap-2">
                        @if($item->icon)
                            <span style="font-size: 1.2rem;">{{ $item->icon }}</span>
                        @endif
                        <span id="preview_text">{{ $item->text ?: 'Preview text will appear here' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-end gap-3">
    <a href="{{ route('admin.cms.header.toolbar.index') }}" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">
        {{ $submitLabel }}
    </button>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const textInput = document.getElementById('toolbar_text');
        const iconInput = document.getElementById('toolbar_icon');
        const textColorInput = document.getElementById('toolbar_text_color');
        const textColorPicker = document.getElementById('toolbar_text_color_picker');
        const preview = document.getElementById('toolbar_preview');
        const previewText = document.getElementById('preview_text');
        const toolbarFontSize = document.getElementById('toolbar_font_size');
        const toolbarFontSizeCustom = document.getElementById('toolbar_font_size_custom');

        function updatePreview() {
            const text = textInput.value || 'Preview text will appear here';
            const icon = iconInput.value || '';
            const textColor = textColorInput.value || '#ffffff';
            
            // Get font size
            let fontSize = toolbarFontSize.value || toolbarFontSizeCustom.value || '';
            let fontSizeValue = '';
            if (fontSize === 'small') fontSizeValue = '0.75rem';
            else if (fontSize === 'medium') fontSizeValue = '0.875rem';
            else if (fontSize === 'large') fontSizeValue = '1rem';
            else if (fontSize) fontSizeValue = fontSize;

            // Update preview text
            if (icon) {
                previewText.innerHTML = '<span style="font-size: 1.2rem;">' + icon + '</span> <span>' + text + '</span>';
            } else {
                previewText.textContent = text;
            }

            // Update text color
            preview.style.color = textColor;
            
            // Update font size
            if (fontSizeValue) {
                previewText.style.fontSize = fontSizeValue;
            }
        }
        
        // Handle custom font size inputs
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
        
        // Sync custom values to hidden inputs before form submit
        document.querySelector('form').addEventListener('submit', function(e) {
            if (toolbarFontSizeCustom.value && !toolbarFontSize.value) {
                toolbarFontSize.value = '';
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'font_size';
                hiddenInput.value = toolbarFontSizeCustom.value;
                this.appendChild(hiddenInput);
            }
        });

        // Sync color picker with text input
        textColorPicker.addEventListener('input', function() {
            textColorInput.value = this.value;
            updatePreview();
        });

        // Sync text input with color picker (when hex color is entered)
        textColorInput.addEventListener('input', function() {
            if (this.value.match(/^#[0-9A-Fa-f]{6}$/)) {
                textColorPicker.value = this.value;
            }
            updatePreview();
        });

        // Update preview on text/icon change
        textInput.addEventListener('input', updatePreview);
        iconInput.addEventListener('input', updatePreview);

        // Initial preview update
        updatePreview();
    });
</script>
@endpush

