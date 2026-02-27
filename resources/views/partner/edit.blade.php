@extends('partner.partner')

@section('content')
    <div class="max-w-5xl mx-auto py-10 px-4">

        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight uppercase">
                    Настройки <span class="text-indigo-600">профиля</span>
                </h1>
                <p class="text-gray-400 font-medium mt-2 text-sm">Управляйте данными вашей компании и юридической информацией</p>
            </div>
            <div class="hidden md:block">
                <span class="bg-indigo-50 text-indigo-600 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest">
                    ID: {{ $user_profile->user_id }}
                </span>
            </div>
        </div>

        <form action="{{ route('partner.profileUpdate') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user_profile->user_id }}">

            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden mb-8">
                <div class="p-8 md:p-12">
                    <h3 class="text-xl font-black text-gray-900 mb-8 flex items-center gap-3">
                        <span class="w-2 h-8 bg-indigo-600 rounded-full"></span>
                        Общая информация
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2 group">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-4">Название компании</label>
                            <input type="text" name="company" value="{{ $user_profile->company }}" required
                                   class="w-full bg-gray-50 border-2 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-0 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 transition-all">
                        </div>

                        <div class="space-y-2 group">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-4">Категория бизнеса</label>
                            <select name="category_id"
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-0 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 transition-all appearance-none cursor-pointer">
                                @foreach($categories as $cat)
                                    <option @if($cat->id == $user_profile->category_id) selected @endif value="{{ $cat->id }}">
                                        {{ $cat->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2 group">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-4">Контактный телефон</label>
                            <input type="text" name="phone" value="{{ $user_profile->phone }}" required
                                   class="w-full bg-gray-50 border-2 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-0 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 transition-all">
                        </div>

                        <div class="space-y-2 group">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-4">Рабочий Email</label>
                            <input type="email" name="email" value="{{ $user_profile->email }}" required
                                   class="w-full bg-gray-50 border-2 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-0 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 transition-all">
                        </div>

                        <div class="space-y-2 group">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-4">Веб-сайт</label>
                            <input type="text" name="site" value="{{ $user_profile->site }}"
                                   class="w-full bg-gray-50 border-2 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-0 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 transition-all">
                        </div>

                        <div class="space-y-2 group">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-4">Юридический адрес</label>
                            <input type="text" name="address" value="{{ $user_profile->address }}" required
                                   class="w-full bg-gray-50 border-2 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-0 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                        <div class="space-y-2 group">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-4">График работы</label>
                            <textarea rows="3" name="work_time"
                                      class="w-full bg-gray-50 border-2 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-0 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 transition-all resize-none">{{ $user_profile->work_time }}</textarea>
                        </div>

                        <div class="space-y-2 group">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-4">О компании</label>
                            <textarea rows="3" name="description"
                                      class="w-full bg-gray-50 border-2 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-0 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 transition-all resize-none">{{ $user_profile->description }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

                <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-xl shadow-gray-200/50 border border-gray-100">
                    <h3 class="text-xl font-black text-gray-900 mb-8 flex items-center gap-3">
                        <span class="w-2 h-8 bg-blue-500 rounded-full"></span>
                        Документы
                    </h3>
                    <div class="space-y-6">
                        <div class="group">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-4 mb-2 block">Логотип компании</label>
                            <input type="file" name="logo" accept="image/*" @if(empty($user_profile->logo)) required @endif
                            class="w-full text-xs text-gray-400 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-600 hover:file:text-white file:transition-all cursor-pointer">
                        </div>
                        <div class="group">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-4 mb-2 block">Договор (PDF)</label>
                            <input type="file" name="agreement" accept="application/pdf" @if(empty($user_profile->agreement)) required @endif
                            class="w-full text-xs text-gray-400 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-600 hover:file:text-white file:transition-all cursor-pointer">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-xl shadow-gray-200/50 border border-gray-100">
                    <h3 class="text-xl font-black text-gray-900 mb-8 flex items-center gap-3">
                        <span class="w-2 h-8 bg-emerald-500 rounded-full"></span>
                        Финансы
                    </h3>
                    <div class="space-y-4">
                        <input type="text" name="bank_name" placeholder="Название банка" value="{{ $user_profile->bank_name }}"
                               class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-500 focus:bg-white focus:ring-0 rounded-2xl py-3 px-6 text-sm font-bold text-gray-700 transition-all">

                        <input type="text" name="bank_account" placeholder="Номер IBAN счета" value="{{ $user_profile->bank_account }}"
                               class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-500 focus:bg-white focus:ring-0 rounded-2xl py-3 px-6 text-sm font-bold text-gray-700 font-mono transition-all">

                        <input type="text" name="card_number" placeholder="Номер банковской карты" value="{{ $user_profile->card_number }}"
                               class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-500 focus:bg-white focus:ring-0 rounded-2xl py-3 px-6 text-sm font-bold text-gray-700 transition-all">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-xl shadow-gray-200/50 border border-gray-100 mb-10">
                <h3 class="text-xl font-black text-gray-900 mb-8 flex items-center gap-3">
                    <span class="w-2 h-8 bg-rose-500 rounded-full"></span>
                    Социальные сети
                </h3>
                @include('partner.partials._networks')
            </div>

            <div class="flex flex-col md:flex-row justify-between items-center gap-4 py-8 border-t border-gray-100">
                <a href="{{ route('partner.cabinet') }}"
                   class="w-full md:w-auto px-10 py-4 bg-white text-gray-400 hover:text-gray-600 font-black uppercase tracking-widest text-[10px] rounded-2xl border border-gray-100 hover:bg-gray-50 transition-all text-center">
                    Отменить изменения
                </a>

                <button type="submit"
                        class="w-full md:w-auto px-12 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black uppercase tracking-widest text-xs rounded-2xl shadow-xl shadow-emerald-100 transition-all active:scale-95">
                    Сохранить профиль
                </button>
            </div>

        </form>
    </div>
@endsection
