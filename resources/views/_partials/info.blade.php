<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.css">

<div class="info" style="display: flex; justify-content: space-around; background: #000000; color: #ffffff; margin-top: 40px;padding: 10px;">
    <div>
        <p>&copy; Paywin.kz 2024</p>
        <p><a style="color: #fff; text-decoration: none;" href="{{ route('aboutUs') }}">О нас</a></p>
        <p><a style="color: #fff; text-decoration: none;" href="{{ asset('files/Политика конфиденциальности.docx') }}" target="_blank">Политика конфиденциальности</a></p>
        <p><a style="color: #fff; text-decoration: none;" @if(Auth::check() && Auth::user()->user_type == 'partner') href="{{ asset('files/Публичная оферта.docx') }}" @else href="{{ asset('files/Публичная оферта.docx') }}" @endif target="_blank">Публичная оферта</a></p>
        <p><a style="color: #fff; text-decoration: none;" href="{{ asset('files/Политика возврата и обмена товаров.docx') }}" target="_blank">Политика возврата и обмена товаров</a></p>
        <p><a style="color: #fff; text-decoration: none;" href="{{ asset('files/Политика проведения онлайн-платежей.docx') }}" target="_blank">Политика проведения онлайн-платежей</a></p>
        <p><a style="color: #fff; text-decoration: none;" href="{{ asset('files/Юридическая информация.docx') }}" target="_blank">Юридическая информация</a></p>
    </div>

    <div style="font-size: 40px; display: flex; align-items: center;">
        <i class="fa fa-cc-visa"></i>&nbsp;&nbsp;<i class="fa fa-cc-mastercard"></i>
    </div>
</div>
