<html>
    <head>
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="d-flex justify-content-center">
        <main class="py-4">
            @yield('content')
        </main>
    </body>
</html>
