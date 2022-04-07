<nav class="cabinet__nav">
    <ul>
        <li><a href="{{ route('partner.cabinet') }}" class="cabinet__nav-link @if(request()->is('partner')) cabinet__nav-link-active @endif">О ПАРТНЕРЕ</a></li>
        <li><a href="{{ route('partner.my-shares.index') }}" class="cabinet__nav-link @if(request()->is('partner/my-shares*')) cabinet__nav-link-active @endif">МОИ АКЦИИ</a></li>
        <li><a href="{{ route('partner.qr') }}" class="cabinet__nav-link @if(request()->is('partner/qr')) cabinet__nav-link-active @endif">МОЙ QR</a></li>
        <li><a href="{{ route('partner.clients') }}" class="cabinet__nav-link @if(request()->is('partner/clients')) cabinet__nav-link-active @endif">КЛИЕНТЫ</a></li>
    </ul>
</nav>
