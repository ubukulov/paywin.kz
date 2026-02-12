<nav class="bg-white shadow-sm cabinet__nav-mobile">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center mt-1">
            <!-- ЛОГОТИП СЛЕВА -->
            <a href="{{ route('home') }}" class="flex items-center font-bold partner-logo-a">
                <img src="/images/logo_bg_white.jpg" alt="logo">
            </a>

            <!-- БУРГЕР СПРАВА -->
            <div class="sm:hidden">
                <button id="burgerBtn" type="button" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- МЕНЮ -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                <a href="{{ route('partner.cabinet') }}" class="nav-link {{ request()->is('partner') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }} px-3 py-2 rounded-md text-sm font-medium">
                    О партнере
                </a>

                <a href="{{ route('partner.my-shares.index') }}" class="nav-link {{ request()->is('partner/my-shares*') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }} px-3 py-2 rounded-md text-sm font-medium">
                    Мои акции
                </a>

                <a href="{{ route('partner.qr') }}" class="nav-link {{ request()->is('partner/qr') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }} px-3 py-2 rounded-md text-sm font-medium">
                    Мой QR
                </a>

                <a href="{{ route('partner.clients') }}" class="nav-link {{ request()->is('partner/clients') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }} px-3 py-2 rounded-md text-sm font-medium">
                    Клиенты
                </a>

                <a href="{{ route('partner.product.index') }}" class="nav-link {{ request()->is('partner/products') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }} px-3 py-2 rounded-md text-sm font-medium">
                    Товары
                </a>

                <a href="{{ route('partner.gift.index') }}" class="nav-link {{ request()->is('partner/gifts') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }} px-3 py-2 rounded-md text-sm font-medium">
                    Подарки
                </a>

                <a href="{{ route('partner.store.index') }}" class="nav-link {{ request()->is('partner/stores') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }} px-3 py-2 rounded-md text-sm font-medium">
                    Магазины
                </a>
            </div>
        </div>
    </div>

    <!-- Мобильное меню -->
    <div id="mobileMenu" class="sm:hidden hidden px-2 pt-2 pb-3 space-y-1">
        <a href="{{ route('partner.cabinet') }}" class="block {{ request()->is('partner') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }} px-3 py-2 rounded-md text-base font-medium">
            О партнере
        </a>
        <a href="{{ route('partner.my-shares.index') }}" class="block {{ request()->is('partner/my-shares*') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }} px-3 py-2 rounded-md text-base font-medium">
            Мои акции
        </a>
        <a href="{{ route('partner.qr') }}" class="block {{ request()->is('partner/qr') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }} px-3 py-2 rounded-md text-base font-medium">
            Мой QR
        </a>
        <a href="{{ route('partner.clients') }}" class="block {{ request()->is('partner/clients') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }} px-3 py-2 rounded-md text-base font-medium">
            Клиенты
        </a>
        <a href="{{ route('partner.product.index') }}" class="block {{ request()->is('partner/products') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }} px-3 py-2 rounded-md text-base font-medium">
            Товары
        </a>
        <a href="{{ route('partner.gift.index') }}" class="block {{ request()->is('partner/gifts') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }} px-3 py-2 rounded-md text-base font-medium">
            Подарки
        </a>
        <a href="{{ route('partner.store.index') }}" class="block {{ request()->is('partner/stores') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }} px-3 py-2 rounded-md text-base font-medium">
            Магазины
        </a>
    </div>

    <script>
        const burgerBtn = document.getElementById('burgerBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        burgerBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</nav>
