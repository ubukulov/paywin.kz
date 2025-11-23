@extends('layouts.app')

@section('content')
    <section class="container mx-auto px-4 py-10">

        <ul class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">

            {{-- Все категории --}}
            <li>
                <a href="{{ route('category.allPartners') }}"
                   class="flex flex-col items-center bg-white shadow-md rounded-xl p-6
                      hover:shadow-lg hover:-translate-y-1 transition duration-200">

                    <img src="/img/icons/all-categories.svg"
                         alt="все категории"
                         class="w-14 h-14 mb-3">

                    <p class="text-gray-800 font-medium text-center">все категории</p>
                </a>
            </li>

            {{-- Категории --}}
            @foreach($categories as $category)
                <li>
                    <a href="{{ route('category.show', ['slug' => $category->slug]) }}"
                       class="flex flex-col items-center bg-white shadow-md rounded-xl p-6
                          hover:shadow-lg hover:-translate-y-1 transition duration-200">

                        <img src="{{ asset($category->iconPath) }}"
                             alt="{{ $category->title }}"
                             class="w-14 h-14 mb-3">

                        <p class="text-gray-800 font-medium text-center">{{ $category->title }}</p>
                    </a>
                </li>
            @endforeach

        </ul>

    </section>
@endsection
