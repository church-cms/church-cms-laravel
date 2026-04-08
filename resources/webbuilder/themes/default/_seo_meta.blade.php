<title>@yield('title', config('settings.sitetitle', $_church->name ?? config('app.name')))</title>
<meta name="description" content="@yield('meta_description', config('settings.sitedescription', ''))">

<meta property="og:title" content="@yield('title', config('settings.sitetitle', $_church->name ?? config('app.name')))">
<meta property="og:description" content="@yield('meta_description', config('settings.sitedescription', ''))">
<meta property="og:type" content="website">
@hasSection('og_image')
<meta property="og:image" content="@yield('og_image')">
@endif

@if(config('settings.favicon'))
<link rel="icon" href="{{ \Storage::url(config('settings.favicon')) }}">
@endif

@stack('meta')
