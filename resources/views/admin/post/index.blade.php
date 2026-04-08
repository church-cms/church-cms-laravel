@extends('layouts.admin.layout')

@section('content')
<div class="flex flex-row justify-between">
    <div class="w-1/2">
        <h1 class="admin-h1">Posts</h1>
    </div>
    <div class="relative flex items-center w-1/2 justify-end">
        <a href="{{ url('/admin/post/add') }}"
            class="no-underline text-white px-4 mx-1 flex items-center custom-green py-1 justify-center rounded">
            <span class="mx-1 text-sm font-semibold">Add Post</span>
            <img src="{{ url('uploads/icons/plus.svg') }}" class="w-3 h-3">
        </a>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ url('/admin/posts') }}" class="my-3 flex flex-wrap items-center gap-3">
    <select name="status"
        class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring focus:ring-indigo-200">
        <option value="">All Statuses</option>
        <option value="posted"    {{ request('status') === 'posted'    ? 'selected' : '' }}>Published</option>
        <option value="drafted"   {{ request('status') === 'drafted'   ? 'selected' : '' }}>Draft</option>
        <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
    </select>
    <select name="category_id"
        class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring focus:ring-indigo-200">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
    </select>
    <button type="submit"
        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-1.5 rounded">Filter</button>
    @if(request('status') || request('category_id'))
    <a href="{{ url('/admin/posts') }}" class="text-sm text-gray-500 hover:underline">Clear</a>
    @endif
</form>

<div class="bg-white my-1 shadow">
    @include('partials.message')

    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-100 text-left text-xs uppercase tracking-wide text-gray-500">
                <th class="px-4 py-3 w-px whitespace-nowrap">#</th>
                <th class="px-4 py-3">Title</th>
                <th class="px-4 py-3">Category</th>
                <th class="px-4 py-3 w-32 whitespace-nowrap">Date</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($posts as $post)
            @php
                $statusMap = [
                    'posted'    => ['bg-green-100 text-green-700',  'Published'],
                    'drafted'   => ['bg-gray-100 text-gray-600',    'Draft'],
                    'pending'   => ['bg-yellow-100 text-yellow-700','Pending'],
                    'cancelled' => ['bg-red-100 text-red-600',      'Cancelled'],
                ];
                [$statusClass, $statusLabel] = $statusMap[$post->status] ?? ['bg-gray-100 text-gray-500', ucfirst($post->status)];
            @endphp
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500">{{ $posts->firstItem() + $loop->index }}</td>
                <td class="px-4 py-3 font-medium text-gray-800 max-w-xs truncate">{{ $post->title }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $post->category->name ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-500 w-32 whitespace-nowrap">
                    {{ $post->post_created_at ? \Carbon\Carbon::parse($post->post_created_at)->format('d M Y') : '—' }}
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center space-x-2">
                        <a href="{{ url('/admin/post/edit/' . $post->id) }}"
                            class="inline-flex items-center gap-1 px-3 py-1 rounded text-xs font-medium bg-indigo-50 text-indigo-700 hover:bg-indigo-100">
                            <img src="{{ url('uploads/icons/edit.svg') }}" class="w-3 h-3"> Edit
                        </a>
                        <button type="button"
                            onclick="deletePost('{{ url('/admin/post/delete/' . $post->id) }}')"
                            class="inline-flex items-center gap-1 px-3 py-1 rounded text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100">
                            <img src="{{ url('uploads/icons/delete.svg') }}" class="w-3 h-3"> Delete
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-6 text-center text-gray-400">No posts found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3">{{ $posts->links() }}</div>
</div>

{{-- Single-row delete form --}}
<form id="deletePostForm" method="POST" action="" style="display:none">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
function deletePost(url) {
    if (!confirm('Are you sure you want to delete this post?')) return;
    const form = document.getElementById('deletePostForm');
    form.action = url;
    form.submit();
}
</script>
@endpush
