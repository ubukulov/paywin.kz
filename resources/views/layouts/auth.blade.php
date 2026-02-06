<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fix.css') }}">
    <script src="{{ asset('js/script.js') }}"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('img/favicons/site.webmanifest') }}">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('css')
</head>
<body class="container registration-1-page" style="padding: 0 10px !important;">
@yield('content')

<script src="{{ asset('js/jquery-3.6.0.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.maskedinput.min.js') }}" type="text/javascript"></script>

@stack('js')
</body>
</html>
