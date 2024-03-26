<nav class="profile__nav">
    <ul>
        <li><a href="{{ route('user.cabinet') }}" class="profile__nav-link @if(request()->is('user')) profile__nav-link-active @endif">ПРОФИЛЬ</a></li>
        <li><a href="{{ route('user.history') }}" class="profile__nav-link @if(request()->is('user/history')) profile__nav-link-active @endif">история</a></li>
        {{--<li><a href="{{ route('user.earn') }}" class="profile__nav-link @if(request()->is('user/earn')) profile__nav-link-active @endif">ЗАРАБОТАТЬ</a></li>--}}
    </ul>
</nav>
