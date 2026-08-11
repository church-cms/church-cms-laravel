@extends('layouts.admin.layout')
@section('content')
    <div class="w-full">
        <!-- lg:w-11/12 -->
        <div>
            <h1 class="admin-h1  flex items-center">
                <a href="{{ url('/admin/gallery') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                    <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
                </a>
                <span class="mx-3">Edit Gallery Album</span>
            </h1>
        </div>
        <div class="bg-white shadow px-2 py-2 my-3">
            <form method="POST" action="{{ url('/admin/gallery/edit/' . $gallery->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="my-3 px-2 py-1">
                    <div class="">
                        <div class="w-full lg:w-1/4">
                            <label for="name" class="tw-form-label">Gallery Name<span
                                    class="text-red-500">*</span></label>
                        </div>
                        <div class="w-full lg:w-2/5 my-2">
                            <input type="text" name="name" id="name" class="tw-form-control w-full"
                                placeholder="Gallery Name" value="{{ $gallery->name }}">
                            <span class="text-danger text-xs">{{ $errors->first('name') }}</span>
                        </div>
                    </div>
                </div>

                <!-- <div class="my-3 px-2 py-1">
                    <div class="">
                        <img src="{{ $gallery->FullPath }}" class="img-responsive w-32 h-32">
                        <div class="">
                            <label class="tw-label cursor-pointer text-xs text-gray-600"> Change Cover Image
                                <input type="file" name="path" id="path">
                            </label>
                        </div>
                    </div>
                    <span class="text-red-500 text-xs font-semibold">{{ $errors->first('path') }}</span>
                </div> -->
                @php
            $currentImagePath = old('cover_image_path', $gallery->FullPath ? $gallery->FullPath : '');
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
                        <div id="folder-tabs" class="hidden flex flex-wrap gap-1 mb-3 pb-3 border-b border-gray-100"></div>
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

                <div class="my-6 px-2">
                    <button class="btn btn-primary blue-bg text-white rounded px-3 py-1 text-sm font-medium">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')

    <style>
        .tw-label {
            color: #3492e2;
        }

        .tw-label input[type="file"] {
            display: none;
        }

    </style>

<script>
    (function() {
        const modal       = document.getElementById('image-picker-modal');
        const openBtn     = document.getElementById('open-picker-btn');
        const closeBtn    = document.getElementById('close-picker-btn');
        const doneBtn     = document.getElementById('picker-done-btn');
        const grid        = document.getElementById('picker-grid');
        const loadingMsg  = document.getElementById('picker-loading');
        const emptyMsg    = document.getElementById('picker-empty');
        const folderTabs  = document.getElementById('folder-tabs');
        const previewWrap = document.getElementById('cover-preview');
        const previewImg  = document.getElementById('cover-preview-img');
        const clearBtn    = document.getElementById('clear-image-btn');
        const btnLabel    = document.getElementById('picker-btn-label');
        const inputId     = document.getElementById('cover_image_id');
        const inputPath   = document.getElementById('cover_image_path');
        const coverError  = document.getElementById('cover-image-error');

        var selectedId    = inputId   ? inputId.value   : '';
        var selectedPath  = inputPath ? inputPath.value : '';
        var imagesLoaded  = false;
        var allImages     = [];
        var currentFolder = 'all';

        function openModal() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            if (!imagesLoaded) loadImages();
        }

        function closeModal() {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        function renderFolderTabs(folders) {
            if (!folderTabs) return;
            folderTabs.innerHTML = '';
            var allFolders = ['all'].concat(folders);
            allFolders.forEach(function(f) {
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = f === 'all' ? 'All' : f;
                btn.dataset.folder = f;
                var isActive = currentFolder === f;
                btn.className = 'text-xs px-3 py-1 rounded-full border transition ' +
                    (isActive ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 border-gray-300 hover:border-indigo-400');
                btn.addEventListener('click', function() {
                    currentFolder = f;
                    renderFolderTabs(folders);
                    renderGrid();
                });
                folderTabs.appendChild(btn);
            });
            folderTabs.classList.remove('hidden');
        }

        function renderGrid() {
            var images = currentFolder === 'all'
                ? allImages
                : allImages.filter(function(img) { return img.folder === currentFolder; });
            grid.innerHTML = '';
            if (images.length === 0) {
                emptyMsg.classList.remove('hidden');
                grid.classList.add('hidden');
                grid.style.display = '';
                return;
            }
            emptyMsg.classList.add('hidden');
            images.forEach(function(img) {
                var div = document.createElement('div');
                div.className = 'cursor-pointer border-2 rounded overflow-hidden transition';
                div.dataset.id  = img.id;
                div.dataset.url = img.url;
                div.classList.add(selectedId == img.id ? 'border-indigo-500' : 'border-transparent');
                div.innerHTML = '<img src="' + img.url + '" class="w-full h-24 object-cover">'
                              + '<p class="text-xs text-gray-600 px-1 py-1 truncate">' + (img.name || '') + '</p>';
                div.addEventListener('click', function() {
                    grid.querySelectorAll('[data-id]').forEach(function(el) {
                        el.classList.remove('border-indigo-500');
                        el.classList.add('border-transparent');
                    });
                    div.classList.add('border-indigo-500');
                    div.classList.remove('border-transparent');
                    selectedId   = img.id;
                    selectedPath = img.url;
                });
                grid.appendChild(div);
            });
            grid.classList.remove('hidden');
            grid.style.display = 'grid';
        }

        function loadImages() {
            loadingMsg.classList.remove('hidden');
            emptyMsg.classList.add('hidden');
            grid.classList.add('hidden');
            grid.style.display = '';
            if (folderTabs) folderTabs.classList.add('hidden');

            fetch(modal.dataset.imagesUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function(r) { return r.json(); })
                .then(function(res) {
                    loadingMsg.classList.add('hidden');
                    allImages = res.data || [];
                    var folders = res.folders || [];
                    if (allImages.length === 0) { emptyMsg.classList.remove('hidden'); return; }
                    renderFolderTabs(folders);
                    renderGrid();
                    imagesLoaded = true;
                })
                .catch(function() { loadingMsg.textContent = 'Failed to load images.'; });
        }

        function applySelection() {
            if (!selectedId) { closeModal(); return; }
            inputId.value   = selectedId;
            inputPath.value = selectedPath;
            previewImg.src  = selectedPath;
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
            renderGrid();
        }

        if (openBtn)  openBtn.addEventListener('click', openModal);
        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (doneBtn)  doneBtn.addEventListener('click', applySelection);
        if (clearBtn) clearBtn.addEventListener('click', clearImage);
        if (modal)    modal.addEventListener('click', function(e) { if (e.target === modal) closeModal(); });

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
            if (refresh) { imagesLoaded = false; }
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
                    method: 'POST', body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(function(r) { return r.json().then(function(data) { return { status: r.status, data: data }; }); })
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
                        setTimeout(function() { closeUploadModal(true); }, 800);
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

        var form = document.querySelector('form[action*="gallery/edit"]');
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