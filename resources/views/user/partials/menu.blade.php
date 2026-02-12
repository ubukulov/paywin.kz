<header class="border-b bg-white">

    <div class="max-w-6xl mx-auto px-4">

        <div class="flex items-center justify-between">

            {{-- LOGO --}}
            <a href="{{ route('home') }}" class="flex items-center font-bold partner-logo-a">
                <img src="/images/logo_bg_white.jpg" alt="logo">
            </a>


            {{-- DESKTOP MENU --}}
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium">

                <a href="{{ route('user.cabinet') }}"
                   class="{{ request()->is('user')
                        ? 'text-blue-600 border-b-2 border-blue-600 pb-1'
                        : 'text-gray-600 hover:text-blue-600' }}">
                    Профиль
                </a>

                <a href="{{ route('user.history') }}"
                   class="{{ request()->is('user/history')
                        ? 'text-blue-600 border-b-2 border-blue-600 pb-1'
                        : 'text-gray-600 hover:text-blue-600' }}">
                    История
                </a>

                <a href="{{ route('user.earn') }}"
                   class="{{ request()->is('user/earn')
                        ? 'text-blue-600 border-b-2 border-blue-600 pb-1'
                        : 'text-gray-600 hover:text-blue-600' }}">
                    Заработать
                </a>

            </nav>


            {{-- BURGER (mobile only) --}}
            <button id="burgerBtn" class="md:hidden p-2">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

        </div>
    </div>


    {{-- MOBILE DROPDOWN --}}
    <nav id="mobileMenu" class="sm:hidden hidden px-2 pt-2 pb-3 space-y-1">

        <a href="{{ route('user.cabinet') }}" class="block {{ request()->is('user') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }} px-3 py-2 rounded-md text-base font-medium">Профиль</a>
        <a href="{{ route('user.history') }}" class="block {{ request()->is('user/history') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }} px-3 py-2 rounded-md text-base font-medium">История</a>
        <a href="{{ route('user.earn') }}" class="block {{ request()->is('user/earn') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }} px-3 py-2 rounded-md text-base font-medium">Заработать</a>

    </nav>
</header>
