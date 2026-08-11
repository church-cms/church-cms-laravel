<div class="space-y-4">
    {{-- Name & Emoji --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Category Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', isset($category) ? $category->name : '') }}"
                   class="form-input w-full @error('name') border-red-500 @enderror"
                   placeholder="e.g. Health, Family, Financial" required>
            @error('name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Emoji</label>
            <input type="text" name="emoji" value="{{ old('emoji', isset($category) ? $category->emoji : '') }}"
                   class="form-input w-full" placeholder="🙏">
            @error('emoji')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- Colors --}}
    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Display Colour</label>
            <div class="flex items-center gap-2">
                <input type="color" name="display_color"
                       value="{{ old('display_color', isset($category) ? $category->display_color : '#4f46e5') }}"
                       class="h-10 w-14 rounded border border-gray-300 cursor-pointer">
                <input type="text" id="display_color_hex"
                       value="{{ old('display_color', isset($category) ? $category->display_color : '#4f46e5') }}"
                       class="form-input flex-1" placeholder="#4f46e5">
            </div>
            @error('display_color')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Gradient Start</label>
            <div class="flex items-center gap-2">
                <input type="color" name="gradient_start"
                       value="{{ old('gradient_start', isset($category) ? $category->gradient_start : '#c7d2fe') }}"
                       class="h-10 w-14 rounded border border-gray-300 cursor-pointer">
                <input type="text" id="gradient_start_hex"
                       value="{{ old('gradient_start', isset($category) ? $category->gradient_start : '#c7d2fe') }}"
                       class="form-input flex-1" placeholder="#c7d2fe">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Gradient End</label>
            <div class="flex items-center gap-2">
                <input type="color" name="gradient_end"
                       value="{{ old('gradient_end', isset($category) ? $category->gradient_end : '#e0e7ff') }}"
                       class="h-10 w-14 rounded border border-gray-300 cursor-pointer">
                <input type="text" id="gradient_end_hex"
                       value="{{ old('gradient_end', isset($category) ? $category->gradient_end : '#e0e7ff') }}"
                       class="form-input flex-1" placeholder="#e0e7ff">
            </div>
        </div>
    </div>

    {{-- Gradient preview --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Gradient Preview</label>
        <div id="gradient-preview" class="h-10 rounded-lg border border-gray-200"
             style="background: linear-gradient(135deg, {{ old('gradient_start', isset($category) ? $category->gradient_start : '#c7d2fe') }}, {{ old('gradient_end', isset($category) ? $category->gradient_end : '#e0e7ff') }})">
        </div>
    </div>

    {{-- CSS class & sort order --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">CSS Class</label>
            <input type="text" name="css_class"
                   value="{{ old('css_class', isset($category) ? $category->css_class : '') }}"
                   class="form-input w-full font-mono" placeholder="prayer-cat-health">
            @error('css_class')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
            <input type="number" name="sort_order" min="0"
                   value="{{ old('sort_order', isset($category) ? $category->sort_order : 0) }}"
                   class="form-input w-full">
        </div>
    </div>

    {{-- Description --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea name="description" rows="2" class="form-input w-full"
                  placeholder="Optional description shown to members">{{ old('description', isset($category) ? $category->description : '') }}</textarea>
    </div>

    {{-- Active toggle --}}
    <div>
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" class="form-checkbox"
                {{ old('is_active', isset($category) ? $category->is_active : true) ? 'checked' : '' }}>
            <span class="text-sm font-medium text-gray-700">Active (visible to members for submission)</span>
        </label>
    </div>
</div>

<script>
(function() {
    const colorInputs = {
        'display_color': 'display_color_hex',
        'gradient_start': 'gradient_start_hex',
        'gradient_end':   'gradient_end_hex',
    };

    function updatePreview() {
        const start = document.querySelector('[name="gradient_start"]').value;
        const end   = document.querySelector('[name="gradient_end"]').value;
        const preview = document.getElementById('gradient-preview');
        if (preview) preview.style.background = 'linear-gradient(135deg, ' + start + ', ' + end + ')';
    }

    Object.keys(colorInputs).forEach(function(name) {
        const pickerEl = document.querySelector('[name="' + name + '"]');
        const hexEl    = document.getElementById(colorInputs[name]);
        if (!pickerEl || !hexEl) return;

        pickerEl.addEventListener('input', function() {
            hexEl.value = pickerEl.value;
            updatePreview();
        });
        hexEl.addEventListener('input', function() {
            if (/^#[0-9a-fA-F]{6}$/.test(hexEl.value)) {
                pickerEl.value = hexEl.value;
                updatePreview();
            }
        });
    });
})();
</script>
