@if(config('settings.logo'))
    <a href="{{ route('web.home') }}">
        <img src="{{ \Storage::url(config('settings.logo')) }}" alt="{{ $_church->name ?? config('app.name') }}" class="h-10 w-auto">
    </a>
@else
    <a href="{{ route('web.home') }}" class="text-xl font-bold text-gray-800">
        {{ $_church->name ?? config('app.name') }}
    </a>
@endif
