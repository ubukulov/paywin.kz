@extends(auth()->user()->user_type === 'user' ? 'user.user' : 'partner.partner')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Управление выплатами</h1>
                <p class="text-gray-500">Создавайте запросы на вывод и отслеживайте их статус</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <div class="lg:col-span-5">
                    <div class="bg-white rounded-[2.5rem] shadow-sm p-8 border border-gray-100">
                        <div class="flex items-center mb-6">
                            <div class="p-3 bg-indigo-50 rounded-2xl mr-4">
                                <svg viewBox="0 0 24 24" class="w-6 h-6" style="width: 24px; height: 24px; color: #5d5fef !important;" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Вывод средств</h2>
                        </div>

                        <form @if(auth()->user()->user_type == 'user') action="{{ route('user.payouts.store') }}" @else action="{{ route('partner.payouts.store') }}" @endif method="POST" class="space-y-6">
                            @csrf

                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2 ml-1">Сумма выплаты (₸)</label>
                                <input type="number" name="amount"
                                       class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all text-lg font-semibold"
                                       placeholder="0" required>
                                @error('amount') <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror

                                @if ($errors->any())
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li class="text-red-500 text-xs mt-1 ml-1">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif

                            </div>

                            @if(auth()->user()->getBalanceAttribute() >= 1000)
                            <button  type="submit"
                                    class="w-full py-4 bg-[#5d5fef] hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-1">
                                Подтвердить вывод
                            </button>

                            @else
                                <div class="flex items-center p-4 bg-amber-50 border border-amber-100 rounded-2xl">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-amber-800">
                                            Минимальная сумма для вывода — **1 000 ₸**.
                                            <span class="block text-xs opacity-75">Ваш текущий баланс: {{ number_format(auth()->user()->getBalanceAttribute(), 0, '.', ' ') }} ₸</span>
                                        </p>
                                    </div>
                                </div>
                            @endif

                        </form>

                        <div class="mt-6 p-4 bg-orange-50 rounded-2xl border border-orange-100">
                            <p class="text-xs text-orange-700 leading-relaxed">
                                * Обработка заявки обычно занимает от 15 минут до 24 часов в рабочее время.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <div class="bg-white rounded-[2.5rem] shadow-sm overflow-hidden border border-gray-100">
                        <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                            <h2 class="text-xl font-bold text-gray-800">История операций</h2>
                            <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
                            Всего: {{ $requests->count() }}
                        </span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 text-gray-400 text-xs uppercase">
                                <tr>
                                    <th class="px-8 py-4 font-medium text-left">Дата и время</th>
                                    <th class="px-8 py-4 font-medium text-left">Сумма</th>
                                    <th class="px-8 py-4 font-medium text-center">Статус</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                @forelse($requests as $req)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-8 py-5">
                                            <div class="text-sm font-medium text-gray-700">{{ $req->created_at->format('d.m.Y') }}</div>
                                            <div class="text-xs text-gray-400">{{ $req->created_at->format('H:i') }}</div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="text-base font-bold text-gray-800">{{ number_format($req->amount, 0, '.', ' ') }} ₸</div>
                                        </td>
                                        <td class="px-8 py-5 text-center">
                                            @if($req->status == 'pending')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-2"></span>
                                                Ожидает
                                            </span>
                                            @elseif($req->status == 'completed')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2"></span>
                                                Выполнено
                                            </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-2"></span>
                                                Отклонено
                                            </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-8 py-12 text-center text-gray-400">
                                            У вас пока нет заявок на вывод
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
