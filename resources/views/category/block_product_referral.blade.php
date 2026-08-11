@if(Auth::check())
    @php
        $agentId = Illuminate\Support\Facades\Auth::id();

        // 1. Ищем акции партнера, активные на текущий момент (по датам)
        $partnerShares = \App\Models\Share::where('partner_id', $product->partner_id)
            ->where(function ($query) {
                $query->whereNull('from_date')->orWhere('from_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('to_date')->orWhere('to_date', '>=', now());
            })
            ->get();

        // 2. Проверяем, есть ли у текущего агента персональный промокод для одной из этих акций
        $myPromo = \App\Models\Promocode::where('agent_id', $agentId)
            ->whereIn('share_id', $partnerShares->pluck('id'))
            ->first();

        if ($myPromo && $myPromo->share) {
            // Если агент уже создал промокод под эту акцию
            $agentPercent = $myPromo->share->real_agent_percent;
            $isExact = true;
        } else {
            // Если промокода нет — берем лучшую акцию из доступных по датам
            $bestShare = $partnerShares->sortByDesc('real_agent_percent')->first();
            $agentPercent = $bestShare ? $bestShare->real_agent_percent : 15;
            $isExact = false;
        }
    @endphp

    <div class="mt-4 p-4 border rounded-lg bg-gray-50 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-bold text-gray-700">Поделись с друзьями и <br>заработай от покупки</span>
            <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded">
                {{ number_format($agentPercent, 1) }}% <br>на карту
            </span>
        </div>

        <div class="relative">
            <input type="text"
                   id="refLinkInput"
                   value="{{ route('user.referral.product', ['agent_id' => auth()->id(), 'slug' => $product->slug]) }}"
                   readonly
                   class="w-full p-2 pr-10 text-xs border rounded bg-white focus:outline-none">

            <button onclick="copyReferralLink()"
                    class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-orange-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                </svg>
            </button>
        </div>
        <p id="copyMessage" class="text-xs text-green-600 mt-2 hidden">Ссылка скопирована в
            буфер!</p>
    </div>
@endif
