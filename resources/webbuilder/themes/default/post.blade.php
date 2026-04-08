@extends('theme::layout')

@section('title', $post->title)
@section('meta_description', Str::limit(strip_tags($post->description), 160))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Back link --}}
    <a href="{{ route('web.posts') }}" class="inline-flex items-center text-sm text-indigo-600 hover:underline mb-6">&larr; Back to Blog</a>

    {{-- Post Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-3">
            <span class="text-sm text-gray-400">{{ \Carbon\Carbon::parse($post->post_created_at)->format('d M Y') }}</span>
            @if($post->category)
            <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600 font-medium">{{ $post->category->name }}</span>
            @endif
        </div>
        <h1 class="text-3xl font-bold text-gray-800 leading-tight">{{ $post->title }}</h1>
    </div>

    {{-- Post Body --}}
    <div class="prose max-w-none text-gray-700 leading-relaxed">
        {!! $post->description !!}
    </div>

    {{-- Tags --}}
    @if($post->tags->count())
    <div class="mt-6 flex flex-wrap items-center gap-2">
        <span class="text-xs text-gray-400 font-medium">Tags:</span>
        @foreach($post->tags as $tag)
        <a href="{{ route('web.posts', ['tag' => $tag->tag_name]) }}"
           class="text-xs px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition">
            #{{ $tag->tag_name }}
        </a>
        @endforeach
    </div>
    @endif

    {{-- Post Like --}}
    <div class="mt-8 flex items-center gap-3">
        <button id="post-like-btn"
            data-id="{{ $post->id }}"
            data-liked="{{ $postLiked ? 'true' : 'false' }}"
            data-url="{{ route('web.post.like', $post->id) }}"
            class="inline-flex items-center gap-2 px-5 py-2 rounded-full border text-sm font-medium transition
                   {{ $postLiked ? 'bg-red-500 border-red-500 text-white' : 'border-gray-300 text-gray-600 hover:border-red-400 hover:text-red-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="{{ $postLiked ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
            </svg>
            <span id="post-like-label">{{ $postLiked ? 'Liked' : 'Like' }}</span>
            <span id="post-like-count" class="ml-1 {{ $postLiked ? 'text-red-200' : 'text-gray-400' }}">{{ $post->public_like_count }}</span>
        </button>
    </div>

    <hr class="my-12 border-gray-200">

    {{-- Comments Section --}}
    <div id="comments">

        {{-- Comment count heading --}}
        <h2 class="text-xl font-bold text-gray-800 mb-6">
            {{ $comments->total() }} {{ Str::plural('Comment', $comments->total()) }}
        </h2>

        {{-- Success flash --}}
        @if(session('comment_success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">
            {{ session('comment_success') }}
        </div>
        @endif

        {{-- Comment list --}}
        @if($comments->count())
        <div class="space-y-6 mb-8">
            @foreach($comments as $comment)
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold text-sm uppercase">
                    {{ strtoupper(substr($comment->user->name ?? $comment->guest_name ?? 'G', 0, 1)) }}
                </div>
                <div class="flex-1 bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-semibold text-gray-700">
                            {{ $comment->user->name ?? $comment->guest_name ?? 'Guest' }}
                        </span>
                        <span class="text-xs text-gray-400">
                            {{ \Carbon\Carbon::parse($comment->created_at)->format('d M Y, g:i A') }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $comment->comments }}</p>
                    {{-- Comment like --}}
                    @php $commentLiked = in_array($comment->id, $likedComments); @endphp
                    <div class="mt-3">
                        <button class="comment-like-btn inline-flex items-center gap-1.5 text-xs font-medium transition
                                       {{ $commentLiked ? 'text-red-500' : 'text-gray-400 hover:text-red-400' }}"
                            data-id="{{ $comment->id }}"
                            data-liked="{{ $commentLiked ? 'true' : 'false' }}"
                            data-url="{{ route('web.comment.like', $comment->id) }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="{{ $commentLiked ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                            </svg>
                            <span class="comment-like-label">{{ $commentLiked ? 'Liked' : 'Like' }}</span>
                            <span class="comment-like-count">{{ $comment->public_like_count > 0 ? $comment->public_like_count : '' }}</span>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($comments->hasPages())
        <div class="mb-8">
            {{ $comments->fragment('comments')->links() }}
        </div>
        @endif

        @else
        <p class="text-sm text-gray-400 mb-8">No comments yet. Be the first to leave one!</p>
        @endif

        {{-- Add Comment Form --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-800 mb-5">Leave a Comment</h3>

            @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 text-sm rounded-lg px-4 py-3 space-y-1">
                @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('web.post.comment', $post->id) }}" novalidate>
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1" for="comment_name">Name <span class="text-red-500">*</span></label>
                        <input id="comment_name" type="text" name="name" value="{{ old('name') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('name') border-red-400 @enderror"
                            placeholder="Your name">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1" for="comment_email">Email <span class="text-red-500">*</span></label>
                        <input id="comment_email" type="email" name="email" value="{{ old('email') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('email') border-red-400 @enderror"
                            placeholder="your@email.com">
                        <p class="text-xs text-gray-400 mt-1">Your email will not be published.</p>
                    </div>
                </div>
                <div class="mb-5">
                    <label class="block text-xs font-medium text-gray-600 mb-1" for="comment_text">Comment <span class="text-red-500">*</span></label>
                    <textarea id="comment_text" name="comment" rows="4"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none @error('comment') border-red-400 @enderror"
                        placeholder="Share your thoughts…">{{ old('comment') }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Comments are moderated before they appear.</p>
                </div>
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition">
                    Submit Comment
                </button>
            </form>
        </div>

    </div>{{-- end #comments --}}

</div>
@endsection

@push('scripts')
<script>
(function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // ── Post like ──────────────────────────────────────────────────
    const postBtn = document.getElementById('post-like-btn');
    if (postBtn) {
        postBtn.addEventListener('click', function () {
            toggleLike(this, {
                url:        this.dataset.url,
                labelEl:    document.getElementById('post-like-label'),
                countEl:    document.getElementById('post-like-count'),
                activeClass:'bg-red-500 border-red-500 text-white',
                inactiveClass:'border-gray-300 text-gray-600 hover:border-red-400 hover:text-red-500',
                countActiveClass:'text-red-200',
                countInactiveClass:'text-gray-400',
            });
        });
    }

    // ── Comment likes ──────────────────────────────────────────────
    document.querySelectorAll('.comment-like-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const labelEl = this.querySelector('.comment-like-label');
            const countEl = this.querySelector('.comment-like-count');
            const svgEl   = this.querySelector('svg');
            const liked   = this.dataset.liked === 'true';

            fetch(this.dataset.url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            })
            .then(r => r.json())
            .then(data => {
                this.dataset.liked = data.liked ? 'true' : 'false';
                labelEl.textContent = data.liked ? 'Liked' : 'Like';
                countEl.textContent = data.count > 0 ? data.count : '';
                if (data.liked) {
                    this.classList.remove('text-gray-400', 'hover:text-red-400');
                    this.classList.add('text-red-500');
                    svgEl.setAttribute('fill', 'currentColor');
                } else {
                    this.classList.remove('text-red-500');
                    this.classList.add('text-gray-400', 'hover:text-red-400');
                    svgEl.setAttribute('fill', 'none');
                }
            })
            .catch(() => {});
        });
    });

    function toggleLike(btn, opts) {
        const liked  = btn.dataset.liked === 'true';
        const svgEl  = btn.querySelector('svg');

        fetch(opts.url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(data => {
            btn.dataset.liked = data.liked ? 'true' : 'false';
            opts.labelEl.textContent = data.liked ? 'Liked' : 'Like';
            opts.countEl.textContent = data.count;

            if (data.liked) {
                btn.className = btn.className.replace(opts.inactiveClass, '').trim();
                btn.classList.add(...opts.activeClass.split(' '));
                opts.countEl.className = opts.countEl.className.replace(opts.countInactiveClass, opts.countActiveClass);
                svgEl.setAttribute('fill', 'currentColor');
            } else {
                btn.classList.remove(...opts.activeClass.split(' '));
                btn.classList.add(...opts.inactiveClass.split(' '));
                opts.countEl.className = opts.countEl.className.replace(opts.countActiveClass, opts.countInactiveClass);
                svgEl.setAttribute('fill', 'none');
            }
        })
        .catch(() => {});
    }
})();
</script>
@endpush
