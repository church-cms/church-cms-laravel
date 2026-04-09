@if(!empty($page))

    @if($page->cover_image)
    <img src="{{ \Storage::url($page->cover_image) }}"
         alt="{{ $page->page_name }}"
         class="w-full h-56 object-cover rounded-lg mb-6">
    @endif

    <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $page->page_name }}</h1>

    <div class="page-content prose max-w-none text-gray-700 leading-relaxed">
        @php
            $rawHtml = ($page->content && !empty($page->content['rendered_html']))
                ? $page->content['rendered_html']
                : ($page->description ?? '');
            $resolvedHtml = \App\Helpers\SiteHelper::resolveWidgetTags($rawHtml);
        @endphp
        {!! $resolvedHtml !!}
    </div>

@endif
