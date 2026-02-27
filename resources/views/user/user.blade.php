<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль</title>
    <script src="{{ asset('css/tailwindcss.css') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/fix.css') }}">
    @stack('user_styles')
</head>
<body>
<div class="container">
    <div class="profile">

        @include('user.partials.menu')

        <div id="content">
            @yield('content')
        </div>

        {{--@include('user.partials.bottom_menu')--}}

        @include('_partials.info')
    </div>
</div>
<script>
    const btn = document.getElementById('burgerBtn')
    const menu = document.getElementById('mobileMenu')

    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden')
    })
</script>
@stack('user_scripts')
</body>
</html>
