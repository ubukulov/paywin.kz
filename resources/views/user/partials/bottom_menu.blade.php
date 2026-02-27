<div class="mt-10 mb-6 px-4">
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-2 flex items-center justify-between max-w-md mx-auto relative">

        <a href="{{ route('user.prizes') }}" class="flex-1 flex justify-center py-3 group">
            <div class="relative">
                <img src="/images/profile/footer-prizes.svg" alt="icon" class="w-6 h-6 opacity-40 group-hover:opacity-100 transition-opacity">
            </div>
        </a>

        <a href="{{ route('home') }}" class="flex-1 flex justify-center py-3 group">
            <div class="p-3 bg-slate-50 rounded-2xl group-hover:bg-blue-50 transition-colors">
                <img src="/img/icons/footer-qr.svg" alt="QR код" class="group-hover:opacity-100 transition-opacity">
            </div>
        </a>

        <a href="{{ route('user.cabinet') }}" class="flex-1 flex justify-center py-3 relative group">
            <div class="absolute -top-1 left-1/2 -translate-x-1/2 w-8 h-1">
                <img src="/images/profile/active-line.svg" alt="" class="w-full h-full object-contain">
            </div>

            <img src="/images/profile/profile.svg" alt="icon" class="w-6 h-6 transition-transform group-hover:scale-110">
        </a>

    </div>
</div>
