<html>

<head>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ url(\Config::get('settings.favicon')) }}">
    <title> ChurchCMS :: Admin Panel</title>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/trix.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @stack('styles')
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
 <style>
      body {
        font-family: Arial, sans-serif;

        height: 100vh;
        gap: 40px;
      }

      .card {
        width: 400px;
        height: 260px;
        border-radius: 15px;
        /* background: url("back.png") no-repeat center/cover; */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        overflow: hidden;
/*        padding: 6px 10px 30px 10px;*/
       padding: 15px 10px 2px 16px;
        color: #000;
          margin-left: auto;
         margin-right: auto;
      }

      .company-name {
        font-size: 20px;
        font-weight: bold;
      }

      .slogan {
        font-size: 12px;
        color: #555;
      }

      .website {
        text-align: center;
        font-size: 13px;
        color: #2196f3;
      }
    </style>

</html>
