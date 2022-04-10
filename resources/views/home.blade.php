@extends('layouts.app')
@section('content')
    <section class="categories container">
        <ul class="categories__list animate__animated animate__fadeIn">
            <li class="categories__item">
                <a href="{{ route('home') }}" class="categories__link">
                    <img src="/img/icons/all-categories.svg" alt="все категории" class="categories__icon">
                    <p class="categories__text">все категории</p>
                </a>
            </li>

            @foreach($categories as $category)
            <li class="categories__item">
                <a href="{{ route('category.show', ['slug' => $category->slug]) }}" class="categories__link">
                    <img src="{{ asset($category->iconPath) }}" alt="{{ $category->title }}" class="categories__icon">
                    <p class="categories__text">{{ $category->title }}</p>
                </a>
            </li>
            @endforeach

        </ul>
    </section>
@endsection
