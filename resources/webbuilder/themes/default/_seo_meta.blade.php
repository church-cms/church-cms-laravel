<title>@yield('title', $_churchdetail['site_title'] ?? $_church->name ?? config('app.name'))</title>
<meta name="description" content="@yield('meta_description', $_churchdetail['site_description'] ?? '')">
@if(!empty($_churchdetail['site_keyword']))
<meta name="keywords" content="{{ $_churchdetail['site_keyword'] }}">
@endif

<meta property="og:title" content="@yield('title', $_churchdetail['site_title'] ?? $_church->name ?? config('app.name'))">
<meta property="og:description" content="@yield('meta_description', $_churchdetail['site_description'] ?? '')">
<meta property="og:type" content="website">
@hasSection('og_image')
<meta property="og:image" content="@yield('og_image')">
@endif

@if(!empty($_churchdetail['favicon']))
<link rel="icon" href="{{ \Storage::url($_churchdetail['favicon']) }}">
@endif

@stack('meta')
