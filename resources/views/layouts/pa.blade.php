<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title : 'Личный кабинет' }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/pa.css?v=0.9.5') }}" rel="stylesheet">

    <!-- Icons !-->
    <link rel="apple-touch-icon" sizes="57x57" href="/image/icons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/image/icons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/image/icons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/image/icons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/image/icons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/image/icons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/image/icons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/image/icons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/image/icons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/image/icons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/image/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/image/icons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/image/icons/favicon-16x16.png">
    <link rel="manifest" href="/image/icons/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/image/icons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
</head>
<body>
    <div class="wrap">
        @yield('content')
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/pa.js?v=0.9.5') }}" defer></script>
</body>
</html>
