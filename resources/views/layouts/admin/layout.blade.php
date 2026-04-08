<html>

<head>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ url(\Config::get('settings.favicon')) }}">
    <title>{{ \config::get('settings.sitetitle') }} :: Control Panel</title>
    @include('layouts.partials._favicon')

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/trix.css') }}">
</head>

<body>

    <div id="app">
        <div class="border-b">
            @include('layouts.partials.navigation')
        </div>

        <div class="flex flex-col lg:flex-row md:flex-row min-h-full relative">
            @include('layouts.admin.sidebar')
            <div class="w-full  bg-gray-300 px-5 py-3">
                <main class="admin-main">
                    @yield('content')

                </main>
            </div>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/trix.js') }}"></script>
    <script src="{{ asset('js/attachments.js') }}"></script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>


    @stack('scripts')



</body>


</html>
