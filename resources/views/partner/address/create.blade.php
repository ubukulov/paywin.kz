@extends('partner.partner')

@section('content')
    <div class="max-w-3xl mx-auto py-10 px-4">

        <div class="mb-8 text-center md:text-left">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight uppercase">
                Новая <span class="text-emerald-600">локация</span>
            </h1>
            <p class="text-gray-400 text-sm font-medium mt-2">Укажите адрес и координаты вашего заведения для отображения на карте</p>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
            <form action="{{ route('partner.addressStore') }}" method="post" class="p-8 md:p-12 space-y-6">
                @csrf

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-4">Полный адрес</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-emerald-500 transition-colors">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <input type="text" name="address" required
                               placeholder="Город, улица, дом.."
                               class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-500 focus:bg-white focus:ring-0 rounded-2xl py-4 pl-14 pr-6 text-sm font-bold text-gray-700 transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-4">Широта (Latitude)</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-emerald-500 transition-colors">
                                <i class="fas fa-arrows-alt-v"></i>
                            </div>
                            <input type="text" name="latitude"
                                   placeholder="Напр: 42.367"
                                   class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-500 focus:bg-white focus:ring-0 rounded-2xl py-4 pl-14 pr-6 text-sm font-bold text-gray-700 transition-all">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-4">Долгота (Longitude)</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-emerald-500 transition-colors">
                                <i class="fas fa-arrows-alt-h"></i>
                            </div>
                            <input type="text" name="longitude"
                                   placeholder="Напр: 69.567"
                                   class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-500 focus:bg-white focus:ring-0 rounded-2xl py-4 pl-14 pr-6 text-sm font-bold text-gray-700 transition-all">
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100 flex items-start gap-3">
                    <i class="fas fa-lightbulb text-amber-500 mt-1"></i>
                    <p class="text-[11px] text-amber-700 font-medium leading-relaxed">
                        Координаты помогают точнее установить метку на карте. Если вы их не знаете, система попытается определить их автоматически по адресу.
                    </p>
                </div>

                <div class="flex flex-col md:flex-row items-center justify-between gap-4 pt-6">
                    <button type="submit"
                            class="w-full md:w-auto px-12 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black uppercase tracking-widest text-xs rounded-2xl shadow-xl shadow-emerald-100 transition-all active:scale-95">
                        Сохранить адрес
                    </button>

                    <a href="{{ route('partner.cabinet') }}"
                       class="w-full md:w-auto px-10 py-4 bg-white text-gray-400 hover:text-gray-600 font-black uppercase tracking-widest text-[10px] rounded-2xl border border-gray-100 hover:bg-gray-50 transition-all text-center">
                        Отмена
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop
