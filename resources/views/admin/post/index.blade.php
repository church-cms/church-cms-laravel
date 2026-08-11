@extends('layouts.admin.layout')

@section('content')
@include('partials._page_header', [
    'pageTitle' => 'Posts',
    'addUrl'    => url('/admin/post/add'),
    'addLabel'  => 'Add Post',
])

{{-- Filter bar --}}
<form method="GET" action="{{ url('/admin/posts') }}"
      class="flex flex-wrap items-center gap-3 bg-white border border-gray-200 rounded-lg px-4 py-3 mb-4 shadow-sm">
    <select name="status"
        class="border border-gray-200 rounded-md px-3 py-1.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400">
        <option value="">All Statuses</option>
        <option value="posted"    {{ request('status') === 'posted'    ? 'selected' : '' }}>Published</option>
        <option value="drafted"   {{ request('status') === 'drafted'   ? 'selected' : '' }}>Draft</option>
        <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
    </select>
    <select name="category_id"
        class="border border-gray-200 rounded-md px-3 py-1.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
    </select>
    <button type="submit"
        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-1.5 rounded-md transition">
        Filter
    </button>
    @if(request('status') || request('category_id'))
    <a href="{{ url('/admin/posts') }}" class="text-sm text-gray-400 hover:text-gray-600 transition">
        &times; Clear
    </a>
    @endif
</form>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    @include('partials.message')

    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-700 text-white text-xs uppercase tracking-wider">
                <th class="px-4 py-3 text-left w-px">#</th>
                <th class="px-4 py-3 text-left">Title</th>
                <th class="px-4 py-3 text-left">Category</th>
                <th class="px-4 py-3 text-left whitespace-nowrap">Date</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($posts as $post)
            @php
                $statusMap = [
                    'posted'    => ['bg-green-100 text-green-700',   'Published'],
                    'drafted'   => ['bg-gray-100 text-gray-600',     'Draft'],
                    'pending'   => ['bg-yellow-100 text-yellow-700', 'Pending'],
                    'cancelled' => ['bg-red-100 text-red-600',       'Cancelled'],
                ];
                [$statusClass, $statusLabel] = $statusMap[$post->status] ?? ['bg-gray-100 text-gray-500', ucfirst($post->status)];
            @endphp
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 text-gray-400 text-xs">{{ $posts->firstItem() + $loop->index }}</td>
                <td class="px-4 py-3 font-medium text-gray-800 max-w-xs truncate">{{ $post->title }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $post->category->name ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-400 text-xs whitespace-nowrap">
                    {{ $post->post_created_at ? \Carbon\Carbon::parse($post->post_created_at)->format('d M Y') : '—' }}
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $statusClass }}">{{ $statusLabel }}</span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-1.5">
                        <a href="{{ url('/admin/post/edit/' . $post->id) }}"
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </a>
                        <button type="button"
                            onclick="deletePost('{{ url('/admin/post/delete/' . $post->id) }}')"
                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100 transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Delete
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-400 text-sm">No posts found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="px-4 py-3 border-t border-gray-100">
        {{ $posts->links() }}
    </div>
</div>

<form id="deletePostForm" method="POST" action="" style="display:none">
    @csrf @method('DELETE')
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
