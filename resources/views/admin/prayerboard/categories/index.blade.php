@extends('layouts.admin.layout')

@section('content')
<div class="flex flex-row justify-between items-center mb-4">
    <h1 class="admin-h1">Prayer Categories</h1>
    <a href="{{ url('/admin/prayercategory/create') }}" class="btn btn-primary">+ Add Category</a>
</div>

@if(session('success'))
<div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger mb-4">{{ session('error') }}</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

    <table class="w-full table-fixed">

        <thead class="bg-gray-50 border-b border-gray-200">

            <tr>

                <th class="w-20 px-5 py-4 text-left text-xs uppercase text-gray-400">
                    ORDER
                </th>

                <th class="w-[38%] px-5 py-4 text-left text-xs uppercase text-gray-400">
                    CATEGORY
                </th>

                <th class="w-32 px-5 py-4 text-left text-xs uppercase text-gray-400">
                    CSS CLASS
                </th>

                <th class="w-24 px-5 py-4 text-left text-xs uppercase text-gray-400">
                    PRAYERS
                </th>

                <th class="w-24 px-5 py-4 text-left text-xs uppercase text-gray-400">
                    STATUS
                </th>

                <th class="w-40 px-5 py-4 text-right text-xs uppercase text-gray-400">
                    ACTIONS
                </th>

            </tr>

        </thead>

        <tbody class="divide-y divide-gray-100">

            @foreach($categories as $cat)

            <tr class="hover:bg-gray-50">

                <!-- Order -->
                <td class="px-5 py-8 align-middle">
                    {{ $cat->sort_order }}
                </td>

                <!-- Category -->
                <td class="px-5 py-8">

                    <div class="flex items-center">

                        <span class="text-3xl mr-5">
                            {{ $cat->emoji }}
                        </span>

                        <div class="flex-1">

                            <h3 class="text-xl font-medium text-gray-800">
                                {{ $cat->name }}
                            </h3>

                            <p class="text-sm text-gray-400">
                                {{ $cat->description }}
                            </p>

                        </div>

                        <span
                            class="w-5 h-5 rounded-full ml-6"
                            style="background:{{ $cat->display_color }}">
                        </span>

                    </div>

                </td>

                <!-- CSS -->
                <td class="px-5 py-8 text-gray-500 leading-6">
                    {!! str_replace('-', '-<br>', e($cat->css_class)) !!}
                </td>

                <!-- Prayer -->
                <td class="px-5 py-8">

                    <div class="text-black leading-6">

                        <div>
                            {{ $cat->prayers()->where('status','PENDING')->count() }}
                            pending
                        </div>

                        <div class="text-green-600">
                            /
                            {{ $cat->prayers()->where('status','ACTIVE')->count() }}
                            active
                        </div>

                    </div>

                </td>

                <!-- Status -->
                <td class="px-5 py-8">

                    @if($cat->is_active)

                        <span class="inline-flex px-4 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
                            Active
                        </span>

                    @else

                        <span class="inline-flex px-4 py-1 rounded-full bg-gray-100 text-gray-600 text-sm font-semibold">
                            Inactive
                        </span>

                    @endif

                </td>

                <!-- Actions -->
                <td class="px-5 py-8 text-right">

                    <div class="flex justify-end items-center gap-2">

                        <a href="{{ url('/admin/prayercategory/edit/'.$cat->id) }}"
                            class="px-5 py-2 text-sm rounded border border-gray-400 bg-gray-100 text-gray-700 hover:bg-gray-200">
                            Edit
                        </a>

                        <form method="POST"
                            action="{{ url('/admin/prayercategory/delete/'.$cat->id) }}"
                            class="inline">

                            @csrf
                            @method('DELETE')

                            <button
                                class="px-5 py-2 text-sm rounded border border-red-400 bg-white text-red-600 hover:bg-red-50">
                                Delete
                            </button>

                        </form>

                    </div>

                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

</div>
@endsection
