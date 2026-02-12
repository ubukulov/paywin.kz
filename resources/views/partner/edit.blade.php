@extends('partner.partner')

@section('content')
    <div class="max-w-5xl mx-auto">

        <form action="{{ route('partner.profileUpdate') }}"
              method="POST"
              enctype="multipart/form-data"
              class="rounded-2xl space-y-10">

            @csrf
            <input type="hidden" name="user_id" value="{{ $user_profile->user_id }}">

            <h2 class="text-2xl font-semibold">Профиль компании</h2>

            {{-- Основные данные --}}
            <div class="grid md:grid-cols-2 gap-6">

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Компания</label>
                    <input type="text" name="company"
                           value="{{ $user_profile->company }}"
                           required
                           class="w-full rounded-lg border border-gray-300 px-4 py-2
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                               outline-none transition">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Категория</label>
                    <select name="category_id"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        @foreach($categories as $cat)
                            <option @if($cat->id == $user_profile->category_id) selected @endif
                            value="{{ $cat->id }}">
                                {{ $cat->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label>Телефон</label>
                    <input type="text" name="phone"
                           value="{{ $user_profile->phone }}"
                           required
                           class="w-full rounded-lg border border-gray-300 px-4 py-2
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                               outline-none transition">
                </div>

                <div class="space-y-2">
                    <label>Адрес</label>
                    <input type="text" name="address"
                           value="{{ $user_profile->address }}"
                           required
                           class="w-full rounded-lg border border-gray-300 px-4 py-2
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                               outline-none transition">
                </div>

                <div class="space-y-2">
                    <label>Email</label>
                    <input type="email" name="email"
                           value="{{ $user_profile->email }}"
                           required
                           class="w-full rounded-lg border border-gray-300 px-4 py-2
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                               outline-none transition">
                </div>

                <div class="space-y-2">
                    <label>Сайт</label>
                    <input type="text" name="site"
                           value="{{ $user_profile->site }}"
                           class="w-full rounded-lg border border-gray-300 px-4 py-2
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                               outline-none transition">
                </div>

            </div>


            {{-- Текстовые поля --}}
            <div class="grid md:grid-cols-2 gap-6">

                <div class="space-y-2">
                    <label>Время работы</label>
                    <textarea rows="3" name="work_time"
                              class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">{{ $user_profile->work_time }}</textarea>
                </div>

                <div class="space-y-2">
                    <label>Описание</label>
                    <textarea rows="3" name="description"
                              class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">{{ $user_profile->description }}</textarea>
                </div>

            </div>


            {{-- Файлы --}}
            <div class="grid md:grid-cols-2 gap-6">

                <div class="space-y-2">
                    <label>Логотип</label>
                    <input type="file"
                           accept="image/*"
                           name="logo"
                           @if(empty($user_profile->logo)) required @endif
                           class="w-full border rounded-lg p-2 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                </div>

                <div class="space-y-2">
                    <label>Договор (PDF)</label>
                    <input type="file"
                           accept="application/pdf"
                           name="agreement"
                           @if(empty($user_profile->agreement)) required @endif
                           class="w-full border rounded-lg p-2 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                </div>

            </div>


            {{-- Реквизиты --}}
            <div class="space-y-6">
                <h3 class="text-lg font-semibold border-b pb-2">Реквизиты</h3>

                <div class="grid md:grid-cols-3 gap-6">

                    <input type="text"
                           name="bank_name"
                           placeholder="Название банка"
                           value="{{ $user_profile->bank_name }}"
                           class="w-full rounded-lg border border-gray-300 px-4 py-2
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                               outline-none transition">

                    <input type="text"
                           name="bank_account"
                           placeholder="Банковский счет"
                           value="{{ $user_profile->bank_account }}"
                           class="w-full rounded-lg border border-gray-300 px-4 py-2
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                               outline-none transition">

                    <input type="text"
                           name="card_number"
                           placeholder="Номер карты"
                           value="{{ $user_profile->card_number }}"
                           class="w-full rounded-lg border border-gray-300 px-4 py-2
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                               outline-none transition">

                </div>
            </div>


            {{-- Соцсети --}}
            <div class="space-y-4">
                <h3 class="text-lg font-semibold border-b pb-2">Социальные сети</h3>
                @include('partner.partials._networks')
            </div>


            {{-- Кнопки --}}
            <div class="flex justify-between pt-6 border-t">

                <a href="{{ route('partner.cabinet') }}"
                   class="px-6 py-2 rounded-xl bg-gray-200 hover:bg-gray-300 transition">
                    Назад
                </a>

                <button type="submit"
                        class="px-6 py-2 rounded-xl bg-green-600 text-white hover:bg-green-700 transition">
                    Сохранить
                </button>

            </div>

        </form>
    </div>
@endsection
