<div class="space-y-4">

    {{-- VK --}}
    <div class="flex items-center gap-3">
        <span class="w-10 text-center text-blue-600 text-xl">
            <i class="fa fa-vk"></i>
        </span>
        <input type="text"
               name="vk"
               value="{{ $user_profile->vk }}"
               placeholder="Ссылка на VK"
               class="w-full rounded-lg border border-gray-300 px-4 py-2
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                               outline-none transition">
    </div>

    {{-- Telegram --}}
    <div class="flex items-center gap-3">
        <span class="w-10 text-center text-blue-400 text-xl">
            <i class="fa fa-telegram"></i>
        </span>
        <input type="text"
               name="telegram"
               value="{{ $user_profile->telegram }}"
               placeholder="Ссылка на Telegram"
               class="w-full rounded-lg border border-gray-300 px-4 py-2
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                               outline-none transition">
    </div>

    {{-- Instagram --}}
    <div class="flex items-center gap-3">
        <span class="w-10 text-center text-pink-500 text-xl">
            <i class="fa fa-instagram"></i>
        </span>
        <input type="text"
               name="instagram"
               value="{{ $user_profile->instagram }}"
               placeholder="Ссылка на Instagram"
               class="w-full rounded-lg border border-gray-300 px-4 py-2
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                               outline-none transition">
    </div>

    {{-- Facebook --}}
    <div class="flex items-center gap-3">
        <span class="w-10 text-center text-blue-700 text-xl">
            <i class="fa fa-facebook"></i>
        </span>
        <input type="text"
               name="facebook"
               value="{{ $user_profile->facebook }}"
               placeholder="Ссылка на Facebook"
               class="w-full rounded-lg border border-gray-300 px-4 py-2
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                               outline-none transition">
    </div>

</div>
