<!-- <!DOCTYPE html> -->
<html lang="en">

<head>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.1.3/tailwind.min.css" rel="stylesheet">
</head>

<body id="app">
    <div class="fixed w-full h-full overflow-auto">

        @yield('content')
        <!-- @include('pages.video.__video-toolbar') -->
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    @stack('scripts')
</body>

</html>
