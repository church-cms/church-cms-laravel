<div class="bg-white shadow p-6 max-w-lg">
    @include('partials.message')
    <div class="mb-4">
        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Country <span class="text-red-500">*</span></label>
        <select name="country_id" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-indigo-200">
            <option value="">— Select Country —</option>
            @foreach($countries as $country)
            <option value="{{ $country->id }}" {{ old('country_id', $city->country_id ?? '') == $country->id ? 'selected' : '' }}>
                {{ $country->name }}
            </option>
            @endforeach
        </select>
        @error('country_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="mb-4">
        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">State <span class="text-red-500">*</span></label>
        <select name="state_id" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-indigo-200">
            <option value="">— Select State —</option>
            @foreach($states as $state)
            <option value="{{ $state->id }}" {{ old('state_id', $city->state_id ?? '') == $state->id ? 'selected' : '' }}>
                {{ $state->name }}
            </option>
            @endforeach
        </select>
        @error('state_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="mb-4">
        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Name <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $city->name ?? '') }}"
            class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-indigo-200">
        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="mb-6">
        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Status <span class="text-red-500">*</span></label>
        <select name="status" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-indigo-200">
            <option value="1" {{ old('status', $city->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('status', $city->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-6 py-2 rounded cursor-pointer">Save</button>
</div>
