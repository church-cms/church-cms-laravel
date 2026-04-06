<div class="bg-white shadow p-6 max-w-lg">
    @include('partials.message')
    <div class="mb-4">
        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Name <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $country->name ?? '') }}"
            class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-indigo-200">
        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="mb-4">
        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Short Name</label>
        <input type="text" name="short_name" value="{{ old('short_name', $country->short_name ?? '') }}"
            class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-indigo-200">
    </div>
    <div class="mb-4">
        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">ISO Code</label>
        <input type="text" name="iso_code" value="{{ old('iso_code', $country->iso_code ?? '') }}"
            class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-indigo-200">
    </div>
    <div class="mb-4">
        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tel Prefix</label>
        <input type="text" name="tel_prefix" value="{{ old('tel_prefix', $country->tel_prefix ?? '') }}"
            class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-indigo-200">
    </div>
    <div class="mb-6">
        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Status <span class="text-red-500">*</span></label>
        <select name="status" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-indigo-200">
            <option value="1" {{ old('status', $country->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('status', $country->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-6 py-2 rounded cursor-pointer">Save</button>
</div>
