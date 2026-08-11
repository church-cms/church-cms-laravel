@extends('layouts.admin.layout')

@section('content')

    <div class="w-full ">
        <!-- lg:w-11/12 -->
        <h1 class="admin-h1 mb-5 flex items-center">
            <a href="{{ url('/admin/group/show/' . $group->id) }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Edit Group</span>
        </h1>

        @include('partials.message')
        <div class="bg-white shadow px-3 py-3">
            <form method="POST" action="{{ url('/admin/group/update/' . $group->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="my-6">
                    <div class="flex items-center">
                        <img src="{{ $group->CoverImagePath }}" class="img-responsive w-12 h-12 rounded-full">
                        <div class="mx-2">
                            <h2 class="font-semibold text-sm text-gray-700">{{ $group->name }}</h2>
                            <label class="tw-label cursor-pointer text-xs text-gray-600"> Change Cover Image<span
                                    class="text-red-500">*</span>
                                <input type="file" size="60" name="cover_image" id="cover_image">
                            </label>
                        </div>
                    </div>
                </div>

                <div class="my-5">
                    <div class="">
                        <div class="w-1/4">
                            <label for="category" class="tw-form-label">Category<span
                                    class="text-red-500">*</span></label>
                        </div>
                        <div class="w-full lg:w-2/5 my-2">
                            <select class="tw-form-control w-full" id="category" name="category">
                                <option value="{{ $group->category_id }}">{{ $group->groupCategory->name }}</option>
                                @foreach ($group_category as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <span class="text-red-500 text-xs font-semibold">{{ $errors->first('category') }}</span>
                    </div>
                </div>

                <div class="my-5">
                    <div class="">
                        <div class="w-1/4">
                            <label for="group_type" class="tw-form-label">Group Type<span
                                    class="text-red-500">*</span></label>
                        </div>
                        <div class="my-2 w-full lg:w-2/5">
                            <select name="group_type" id="group_type" class="tw-form-control w-full">
                                <option value="{{ $group->group_type }}">{{ ucwords($group->group_type) }}</option>
                                <option value="common_interests">Common Interests</option>
                                <option value="everyone">Everyone</option>
                                <option value="married_couples">Married Couples</option>
                                <option value="men">Men</option>
                                <option value="women">Women</option>
                                <option value="young_adults">Young Adults</option>
                                <option value="youth">Youth</option>
                            </select>
                            <span class="text-red-500 text-xs font-semibold">{{ $errors->first('group_type') }}</span>
                        </div>
                    </div>
                </div>

                <div class="my-5">
                    <div class="">
                        <div class="w-1/4">
                            <label for="name" class="tw-form-label">Group Name<span class="text-red-500">*</span></label>
                        </div>
                        <div class="w-full lg:w-2/5 my-2">
                            <input type="text" name="name" id="name" class="tw-form-control w-full"
                                value="{{ $group->name }}">
                        </div>
                        <span class="text-red-500 text-xs font-semibold">{{ $errors->first('name') }}</span>
                    </div>
                </div>

                <div class="my-5">
                    <div class="">
                        <div class="w-1/4">
                            <label for="description" class="tw-form-label">Description<span
                                    class="text-red-500">*</span></label>
                        </div>
                        <div class="w-full lg:w-2/5 my-2">
                            <textarea type="text" name="description" id="description" class="tw-form-control w-full"
                                rows="3">{{ $group->description }}</textarea>
                        </div>
                        <span class="text-red-500 text-xs font-semibold">{{ $errors->first('description') }}</span>
                    </div>
                </div>

                <div class="my-6">
                    <input type="submit" id="submit-btn" value="Submit" name="submit"
                        class="btn btn-primary submit-btn cursor-pointer">
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

@endpush
