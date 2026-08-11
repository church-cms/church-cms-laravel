@extends('layouts.admin.layout')

@section('content')
<div class="w-full">
    <h1 class="admin-h1 mb-5 flex items-center">
        <a href="{{ url('/admin/bulletins') }}" title="Back" class="rounded-full bg-gray-300 p-2">
            <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
        </a>
        <span class="mx-3">Edit Bulletin</span>
    </h1>

    @include('partials.message')

    <div class="bg-white shadow px-4 py-3">
        <form method="POST" action="{{ url('/admin/bulletin/update/' . $bulletin->id) }}" enctype="multipart/form-data">
            @csrf

            {{-- Name --}}
            <div class="my-5">
                <div class="w-full lg:w-1/4">
                    <label for="name" class="tw-form-label">Bulletin Name <span class="text-red-500">*</span></label>
                </div>
                <div class="w-full lg:w-2/5 my-2">
                    <input type="text" name="name" id="name"
                        value="{{ old('name', $bulletin->name) }}"
                        class="tw-form-control w-full" placeholder="Enter name of Bulletin">
                    <span class="text-red-500 text-xs font-semibold">{{ $errors->first('name') }}</span>
                </div>
            </div>

            {{-- Year --}}
            <div class="my-5">
                <div class="w-full lg:w-1/4">
                    <label for="year" class="tw-form-label">Year <span class="text-red-500">*</span></label>
                </div>
                <div class="w-full lg:w-2/5 my-2">
                    <select name="year" id="year" class="tw-form-control w-full">
                        <option value="" disabled>Select Year</option>
                        @php $startYear = (int) date('Y'); $endYear = $startYear - 25; @endphp
                        @for ($y = $startYear; $y >= $endYear; $y--)
                            <option value="{{ $y }}" {{ old('year', $bulletin->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <span class="text-red-500 text-xs font-semibold">{{ $errors->first('year') }}</span>
                </div>
            </div>

            {{-- Type --}}
            <div class="my-5">
                <div class="w-full lg:w-1/4">
                    <label for="type" class="tw-form-label">Type <span class="text-red-500">*</span></label>
                </div>
                <div class="w-full lg:w-2/5 my-2">
                    <select name="type" id="type" class="tw-form-control w-full">
                        <option value="" disabled>Select Type</option>
                        <option value="week"  {{ old('type', $bulletin->type) === 'week'  ? 'selected' : '' }}>Week</option>
                        <option value="month" {{ old('type', $bulletin->type) === 'month' ? 'selected' : '' }}>Month</option>
                    </select>
                    <span class="text-red-500 text-xs font-semibold">{{ $errors->first('type') }}</span>
                </div>
            </div>

            {{-- Week --}}
            @php $currentType = old('type', $bulletin->type); @endphp
            <div class="my-5" id="week-field" style="{{ $currentType === 'week' ? '' : 'display:none' }}">
                <div class="w-full lg:w-1/4">
                    <label for="week" class="tw-form-label">Week <span class="text-red-500">*</span></label>
                </div>
                <div class="w-full lg:w-2/5 my-2">
                    <select name="week" id="week" class="tw-form-control w-full">
                        <option value="" disabled>Select Week</option>
                        @for ($w = 1; $w <= 52; $w++)
                            <option value="{{ $w }}" {{ old('week', $bulletin->week) == $w ? 'selected' : '' }}>{{ $w }}</option>
                        @endfor
                    </select>
                    <span class="text-red-500 text-xs font-semibold">{{ $errors->first('week') }}</span>
                </div>
            </div>

            {{-- Month --}}
            @php
            $months = [
                '01' => 'January',  '02' => 'February', '03' => 'March',
                '04' => 'April',    '05' => 'May',       '06' => 'June',
                '07' => 'July',     '08' => 'August',    '09' => 'September',
                '10' => 'October',  '11' => 'November',  '12' => 'December',
            ];
            @endphp
            <div class="my-5" id="month-field" style="{{ $currentType === 'month' ? '' : 'display:none' }}">
                <div class="w-full lg:w-1/4">
                    <label for="month" class="tw-form-label">Month <span class="text-red-500">*</span></label>
                </div>
                <div class="w-full lg:w-2/5 my-2">
                    <select name="month" id="month" class="tw-form-control w-full">
                        <option value="" disabled>Select Month</option>
                        @foreach ($months as $num => $name)
                            <option value="{{ $num }}" {{ old('month', $bulletin->month) == $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    <span class="text-red-500 text-xs font-semibold">{{ $errors->first('month') }}</span>
                </div>
            </div>

            {{-- Cover Image --}}

            <!-- <div class="my-5">
                <div class="w-full lg:w-1/4">
                    <label for="cover_image" class="tw-form-label">Cover Image</label>
                </div>
                <div class="w-full lg:w-2/5 my-2">
                    @if ($bulletin->cover_image)
                        <div class="mb-2">
                            <img src="{{ $bulletin->CoverImagePath }}" class="w-24 h-24 object-cover rounded border border-gray-200">
                            <p class="text-xs text-gray-500 mt-1">Current image — upload a new one to replace it.</p>
                        </div>
                    @endif
                    <input type="file" name="cover_image" id="cover_image"
                        accept=".jpg,.jpeg,.png,.webp"
                        class="tw-form-control w-full">
                    <span class="text-red-500 text-xs font-semibold">{{ $errors->first('cover_image') }}</span>
                </div>
            </div> -->

            @php
            $currentImagePath = old('cover_image_path', $bulletin->cover_image ? $bulletin->CoverImagePath : '');
            @endphp

            {{-- ── Cover Image ──────────────────────────────────────────────────── --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-5">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-700">Cover Image <span class="text-gray-400 font-normal text-xs ml-1"></span></h2>
                </div>
                <div class="px-6 py-5">
                    <input type="hidden" name="cover_image_id" id="cover_image_id" value="{{ old('cover_image_id') }}">
                    <input type="hidden" name="cover_image_path" id="cover_image_path" value="{{ $currentImagePath }}">

                    <div id="cover-preview" class="{{ $currentImagePath ? '' : 'hidden' }} mb-3">
                        <img id="cover-preview-img"
                            src="{{ $currentImagePath }}"
                            class="w-full max-w-xs h-32 object-cover rounded-lg border border-gray-200">
                    </div>

                    <div class="flex gap-3 items-center">
                        <button type="button" id="open-picker-btn"
                            class="text-sm text-indigo-600 border border-indigo-300 rounded px-3 py-1.5 hover:bg-indigo-50 transition">
                            <i class="fas fa-images mr-1"></i>
                            <span id="picker-btn-label">{{ $currentImagePath ? 'Change Image' : 'Pick from Media Library' }}</span>
                        </button>
                        <button type="button" id="clear-image-btn"
                            class="{{ $currentImagePath ? '' : 'hidden' }} text-sm text-red-400 hover:text-red-600">
                            <i class="fas fa-times mr-1"></i>Remove
                        </button>
                    </div>
                    <p id="cover-image-error" class="hidden text-red-500 text-xs mt-2">Please select a cover image.</p>
                </div>
            </div>

            {{-- Image Picker Modal — starts hidden; JS adds 'flex' when opening --}}
            <div id="image-picker-modal"
                data-images-url="{{ url('/admin/mediafile/images') }}"
                class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center p-4">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl flex flex-col" style="max-height:80vh">
                    <div class="flex justify-between items-center px-6 py-4 border-b flex-shrink-0">
                        <h2 class="text-base font-semibold">Pick a Cover Image</h2>
                        <div class="flex items-center gap-3">
                            <button type="button" id="add-media-btn"
                                data-upload-url="{{ url('/admin/mediafile/image/create') }}"
                                class="text-xs text-green-700 border border-green-400 rounded px-3 py-1.5 hover:bg-green-50 transition flex items-center gap-1">
                                <i class="fas fa-plus text-xs"></i> Add Media image
                            </button>
                            <button type="button" id="close-picker-btn" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
                        </div>
                    </div>
                    <div class="px-6 py-4 flex-1 overflow-y-auto">
                        <p id="picker-loading" class="text-sm text-gray-400 py-4 text-center">Loading images…</p>
                        <p id="picker-empty" class="hidden text-sm text-gray-500 py-4">
                            No images in the media library. Click <strong>Add Media image</strong> above to upload.
                        </p>
                        <div id="picker-grid" class="hidden gap-3" style="grid-template-columns: repeat(3, minmax(0, 1fr))"></div>
                    </div>
                    <div class="flex justify-end px-6 py-3 border-t flex-shrink-0">
                        <button type="button" id="picker-done-btn"
                            class="blue-bg text-white text-sm px-4 py-1.5 rounded">Done</button>
                    </div>
                </div>
            </div>
            {{-- Upload modal (nested, z-60) --}}
            <div id="upload-media-modal"
                data-store-url="{{ url('/admin/mediafile/image/create') }}"
                class="hidden fixed inset-0 bg-black bg-opacity-60 z-60 items-center justify-center p-4">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
                    <div class="flex justify-between items-center px-6 py-3 border-b">
                        <h2 class="text-sm font-semibold">Upload New Image</h2>
                        <button type="button" id="close-upload-modal-btn" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <input type="hidden" id="upload-csrf" value="{{ csrf_token() }}">
                        <div id="upload-result" class="hidden text-sm rounded px-3 py-2"></div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Image Name <span class="text-red-500">*</span></label>
                            <input type="text" id="upload-name" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400" placeholder="e.g. Sunday Worship">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Image File <span class="text-red-500">*</span></label>
                            <input type="file" id="upload-file" accept=".jpg,.jpeg,.png,.wmp" class="w-full text-sm border border-gray-300 rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Description</label>
                            <input type="text" id="upload-description" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400" placeholder="Optional">
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 px-6 py-3 border-t">
                        <button type="button" id="close-upload-modal-btn2" class="text-sm text-gray-500 border border-gray-300 rounded px-4 py-1.5 hover:bg-gray-50">Cancel</button>
                        <button type="button" id="upload-submit-btn" class="blue-bg text-white text-sm px-4 py-1.5 rounded">Upload</button>
                    </div>
                </div>
            </div>

            {{-- Bulletin File --}}
            <div class="my-5">
                <div class="w-full lg:w-1/4">
                    <label for="path" class="tw-form-label">Bulletin File</label>
                </div>
                <div class="w-full lg:w-2/5 my-2">
                    @if ($bulletin->path)
                        <div class="mb-2">
                            <a href="{{ url('/admin/bulletin/download/' . $bulletin->id) }}"
                               target="_blank"
                               class="text-xs text-indigo-600 hover:underline">
                                <i class="fas fa-file-pdf mr-1"></i>View Current File
                            </a>
                            <p class="text-xs text-gray-500 mt-1">Upload a new PDF only if you want to replace it.</p>
                        </div>
                    @endif
                    <input type="file" name="path" id="path"
                        accept=".pdf"
                        class="tw-form-control w-full">
                    <span class="text-red-500 text-xs font-semibold">{{ $errors->first('path') }}</span>
                </div>
            </div>

            <div class="my-6">
                <button type="submit" class="btn btn-primary blue-bg text-white rounded px-3 py-1 text-sm font-medium">Update</button>
                <a href="{{ url('/admin/bulletins') }}" class="btn bg-gray-100 text-gray-700 border rounded px-3 py-1 ml-2 text-sm font-medium no-underline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var typeSelect = document.getElementById('type');
        var weekField  = document.getElementById('week-field');
        var monthField = document.getElementById('month-field');

        function toggleFields() {
            var val = typeSelect.value;
            weekField.style.display  = val === 'week'  ? '' : 'none';
            monthField.style.display = val === 'month' ? '' : 'none';
        }

        if (typeSelect) typeSelect.addEventListener('change', toggleFields);
    })();

    (function() {
        // ── Cover image picker ───────────────────────────────────────────────
        const modal = document.getElementById('image-picker-modal');
        const openBtn = document.getElementById('open-picker-btn');
        const closeBtn = document.getElementById('close-picker-btn');
        const doneBtn = document.getElementById('picker-done-btn');
        const grid = document.getElementById('picker-grid');
        const loadingMsg = document.getElementById('picker-loading');
        const emptyMsg = document.getElementById('picker-empty');
        const previewWrap = document.getElementById('cover-preview');
        const previewImg = document.getElementById('cover-preview-img');
        const clearBtn = document.getElementById('clear-image-btn');
        const btnLabel = document.getElementById('picker-btn-label');
        const inputId = document.getElementById('cover_image_id');
        const inputPath = document.getElementById('cover_image_path');

        var selectedId = inputId ? inputId.value : '';
        var selectedPath = inputPath ? inputPath.value : '';
        var imagesLoaded = false;
        var coverError = document.getElementById('cover-image-error');

        function openModal() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            if (!imagesLoaded) loadImages();
        }

        function closeModal() {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        function loadImages() {
            loadingMsg.classList.remove('hidden');
            emptyMsg.classList.add('hidden');
            grid.classList.add('hidden');
            grid.style.display = '';

            fetch(modal.dataset.imagesUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(res) {
                    loadingMsg.classList.add('hidden');
                    var images = res.data || [];
                    if (images.length === 0) {
                        emptyMsg.classList.remove('hidden');
                        return;
                    }
                    grid.innerHTML = '';
                    images.forEach(function(img) {
                        var div = document.createElement('div');
                        div.className = 'cursor-pointer border-2 rounded overflow-hidden transition';
                        div.dataset.id = img.id;
                        div.dataset.url = img.url;
                        div.dataset.name = img.name || '';
                        div.classList.add(selectedId == img.id ? 'border-indigo-500' : 'border-transparent');
                        div.innerHTML =
                            '<img src="' + img.url + '" class="w-full h-24 object-cover">' +
                            '<p class="text-xs text-gray-600 px-1 py-1 truncate">' + (img.name || '') + '</p>';
                        div.addEventListener('click', function() {
                            grid.querySelectorAll('[data-id]').forEach(function(el) {
                                el.classList.remove('border-indigo-500');
                                el.classList.add('border-transparent');
                            });
                            div.classList.add('border-indigo-500');
                            div.classList.remove('border-transparent');
                            selectedId = img.id;
                            selectedPath = img.url;
                        });
                        grid.appendChild(div);
                    });
                    grid.classList.remove('hidden');
                    grid.style.display = 'grid';
                    imagesLoaded = true;
                })
                .catch(function() {
                    loadingMsg.textContent = 'Failed to load images.';
                });
        }

        function applySelection() {
            if (!selectedId) {
                closeModal();
                return;
            }
            inputId.value = selectedId;
            inputPath.value = selectedPath;
            previewImg.src = selectedPath;
            previewWrap.classList.remove('hidden');
            clearBtn.classList.remove('hidden');
            btnLabel.textContent = 'Change Image';
            if (coverError) coverError.classList.add('hidden');
            closeModal();
        }

        function clearImage() {
            selectedId = selectedPath = '';
            inputId.value = inputPath.value = '';
            previewWrap.classList.add('hidden');
            clearBtn.classList.add('hidden');
            btnLabel.textContent = 'Pick from Media Library';
            if (grid) grid.querySelectorAll('[data-id]').forEach(function(el) {
                el.classList.remove('border-indigo-500');
                el.classList.add('border-transparent');
            });
        }

        if (openBtn) openBtn.addEventListener('click', openModal);
        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (doneBtn) doneBtn.addEventListener('click', applySelection);
        if (clearBtn) clearBtn.addEventListener('click', clearImage);
        if (modal) modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });

        // ── Upload media modal ───────────────────────────────────────────────
        var uploadModal     = document.getElementById('upload-media-modal');
        var addMediaBtn     = document.getElementById('add-media-btn');
        var closeUploadBtn  = document.getElementById('close-upload-modal-btn');
        var closeUploadBtn2 = document.getElementById('close-upload-modal-btn2');
        var uploadSubmitBtn = document.getElementById('upload-submit-btn');
        var uploadResult    = document.getElementById('upload-result');

        function openUploadModal() {
            closeModal();
            document.getElementById('upload-name').value = '';
            document.getElementById('upload-file').value = '';
            document.getElementById('upload-description').value = '';
            uploadResult.className = 'hidden text-sm rounded px-3 py-2';
            uploadResult.textContent = '';
            uploadModal.classList.remove('hidden');
            uploadModal.classList.add('flex');
        }

        function closeUploadModal(refresh) {
            uploadModal.classList.remove('flex');
            uploadModal.classList.add('hidden');
            if (refresh) {
                imagesLoaded = false;
                loadImages();
            }
            openModal();
        }

        if (addMediaBtn)     addMediaBtn.addEventListener('click', openUploadModal);
        if (closeUploadBtn)  closeUploadBtn.addEventListener('click', function() { closeUploadModal(false); });
        if (closeUploadBtn2) closeUploadBtn2.addEventListener('click', function() { closeUploadModal(false); });
        if (uploadModal)     uploadModal.addEventListener('click', function(e) {
            if (e.target === uploadModal) closeUploadModal(false);
        });

        if (uploadSubmitBtn) {
            uploadSubmitBtn.addEventListener('click', function() {
                var name = document.getElementById('upload-name').value.trim();
                var file = document.getElementById('upload-file').files[0];
                if (!name || !file) {
                    uploadResult.className = 'text-sm rounded px-3 py-2 bg-red-50 text-red-600 border border-red-200';
                    uploadResult.textContent = 'Name and image file are required.';
                    return;
                }
                var formData = new FormData();
                formData.append('name', name);
                formData.append('image', file);
                formData.append('description', document.getElementById('upload-description').value);
                formData.append('_token', document.getElementById('upload-csrf').value);

                uploadSubmitBtn.disabled = true;
                uploadSubmitBtn.textContent = 'Uploading…';

                fetch(uploadModal.dataset.storeUrl, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(function(r) {
                    return r.json().then(function(data) {
                        return { status: r.status, data: data };
                    });
                })
                .then(function(res) {
                    uploadSubmitBtn.disabled = false;
                    uploadSubmitBtn.textContent = 'Upload';
                    if (res.status === 422 && res.data.errors) {
                        var msgs = Object.values(res.data.errors).flat();
                        uploadResult.className = 'text-sm rounded px-3 py-2 bg-red-50 text-red-600 border border-red-200';
                        uploadResult.textContent = msgs[0] || 'Validation failed.';
                    } else if (res.data.success) {
                        uploadResult.className = 'text-sm rounded px-3 py-2 bg-green-50 text-green-700 border border-green-200';
                        uploadResult.textContent = res.data.success;
                        setTimeout(function() {
                            closeUploadModal(true);
                        }, 800);
                    } else {
                        uploadResult.className = 'text-sm rounded px-3 py-2 bg-red-50 text-red-600 border border-red-200';
                        uploadResult.textContent = res.data.error || 'Upload failed.';
                    }
                })
                .catch(function() {
                    uploadSubmitBtn.disabled = false;
                    uploadSubmitBtn.textContent = 'Upload';
                    uploadResult.className = 'text-sm rounded px-3 py-2 bg-red-50 text-red-600 border border-red-200';
                    uploadResult.textContent = 'Upload failed. Please try again.';
                });
            });
        }

        var form = document.querySelector('form[action*="sermon/edit"]');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!inputPath || !inputPath.value) {
                    e.preventDefault();
                    if (coverError) coverError.classList.remove('hidden');
                    openBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        }
    })();
</script>
@endpush
