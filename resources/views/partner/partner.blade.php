<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>О партнере</title>
    <link rel="stylesheet" href="/css/style.min.css">
    <link rel="stylesheet" href="{{ asset('css/fix.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('partner_styles')
    <style>
        #content {
            max-width: 450px;
            margin: 0 auto;
        }
        .partner__address-upload-btn {
            font-size: 12px !important;
        }
        .cabinet .cabinet__nav-link {
            font-size: 14px !important;
        }
        .tabcontent__slider-btn {
            width: 140px;
            text-align: center;
        }
        .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
            background-color: orange;
            color: #fff;
        }
        .promo__prise-form {
            padding: 20px;
        }
        .time-s,.time-po {
            width: 130px !important;
        }
        @media only screen and (max-width: 480px) {
            .promo__nav-link {
                font-size: 15px !important;
            }
        }

    </style>
</head>
<body>

<div class="container">
    <div class="cabinet">
        <header class="cabinet__header">
            @include('partner.partials.menu')
        </header>

        <div id="content">
            @yield('content')
        </div>

        @include('_partials.info')
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js"></script>
<script src="https://use.fontawesome.com/3a79d193bf.js"></script>
@stack('partner_scripts')
</body>
</html>
