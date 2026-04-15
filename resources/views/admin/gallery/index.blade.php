@extends('layouts.admin.layout')
@section('content')
    <div class="flex lg:items-center justify-between flex-col lg:flex-row md:flex-row">
        <h1 class="admin-h1">Gallery ({{ $count }})</h1>

        <form method="GET" action="{{ url('/admin/gallery') }}" role="search" enctype="multipart/form-data"
            class="mb-0">
            <div class="">
                <div class="flex lg:justify-end md:justify-end items-center">
                    <div class="search relative w-48">
                        <input type="text" name="search" class="tw-form-control w-full relative" placeholder="Search"
                            value="{{ old('search') }}">
                        <button class="no-underline text-white px-4 mx-1 py-1 absolute right-0 focus:outline-none">
                            <img src="{{ url('uploads/icons/search.svg') }}"
                                class="w-4 h-4 absolute right-0 mt-2 mx-1 top-0">
                        </button>
                    </div>
                    <div class="date-select date-select_none dashboard-reset mx-1 lg:mx-0 md:mx-0">
                        <a href="{{ url('/admin/gallery') }}" id="do-reset"
                            class="text-sm border bg-gray-100 text-grey-darkest py-1 px-4">Reset</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @include('partials.message')
    <div class="bg-white flex flex-wrap px-2 my-3">
      
            <div class="w-full lg:w-1/6 md:w-1/2 px-2 my-5">
                <a href="{{ url('/admin/gallery/create') }}" id="add">
                    <div class="border border-dashed border-gray-300 flex items-center h-40">
                        <img src="{{ url('uploads/icons/add-gallery.svg') }}" class="w-8 h-8 mx-auto">
                    </div>
                </a>
                <div>
                    <p class="text-sm font-semibold my-2 text-gray-800">
                        <a href="{{ url('/admin/gallery/create') }}">New Album</a>
                    </p>
                </div>
            </div>
       

        @foreach ($gallery as $gallery)
            <div class="w-full lg:w-1/4 md:w-1/2 px-2 my-5">
                <div class="shadow-md">
                    <div class="flex p-2">
                        <img class="card-img-top h-40 w-5/6" src="{{ $gallery->FullPath }}" alt="{{ $gallery->name }}">
                        <div class="">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <a href="{{ url('/admin/gallery/edit/' . $gallery->id) }}" id="edit_permission"
                                        class="left-auto" title="Edit Gallery">
                                        <img src="{{ url('/uploads/icons/edit.svg') }}" class="w-3 h-3 m-3">
                                    </a>
                                    <a href="#" rel="{{ url('/admin/gallery/delete/' . $gallery->id) }}"
                                        id="remove_member" class="left-auto delete-gallery">
                                        <img src="{{ url('/uploads/icons/cancel.svg') }}" class="w-3 h-3 m-3">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="leading-loose my-1">
                    <p class="text-sm font-semibold text-gray-800 capitalize">
                        <a href="{{ url('/admin/gallery/' . $gallery->id) }}">{{ $gallery->name }}</a>
                    </p>
                    <p class="text-xs text-gray-700 flex items-center">
                        <img src="{{ url('uploads/icons/img-gallery.svg') }}" class="w-3 h-3">
                        <span class="mx-2">{{ $gallery->getPhotoCount($gallery->id) }} photos</span>
                    </p>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.delete-gallery').on('click', function() {
                var link = $(this).attr('rel');
                swal({
                    icon: "info",
                    text: "Do you want to delete this gallery ?",
                    buttons: {
                        cancel: true,
                        confirm: true,
                    },
                    allowOutsideClick: false,
                }).then((willChange) => {
                    if (willChange) {
                        $.ajax({
                            url: link,
                            type: "DELETE",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                swal({
                                    icon: "success",
                                    text: "Gallery Deleted Successfully",
                                }).then(function() {
                                    window.location.href = '/admin/gallery';
                                });
                            }
                        })
                    } else {
                        swal("Cancelled");
                    }
                });
            });
        });
    </script>
@endpush
