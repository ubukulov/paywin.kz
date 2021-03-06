<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>О партнере</title>
    <!-- Bootstrap CSS -->
{{--    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">--}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.min.css">
    @stack('partner_styles')
    <style>
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
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
<script src="https://use.fontawesome.com/3a79d193bf.js"></script>
@stack('partner_scripts')
</body>
</html>
