{{-- БЛОК ОТЗЫВОВ --}}
<div class="mt-8 bg-white p-6 rounded-2xl shadow-sm grid grid-cols-1 lg:grid-cols-3 gap-8" id="reviews-section">

    {{-- Левая колонка: Средний рейтинг --}}
    <div class="space-y-4">
        <h3 class="text-lg font-bold text-gray-900">Отзывы покупателей</h3>
        <div class="flex items-center gap-4">
            <div class="text-5xl font-black text-gray-900">{{ $product->average_rating }}</div>
            <div>
                {{-- Вывод статичных звезд в зависимости от рейтинга --}}
                <div class="flex items-center text-amber-400">
                    @for($i = 1; $i <= 5; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ $i <= $product->average_rating ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endfor
                </div>
                <p class="text-xs text-gray-400 mt-1">На основе {{ $product->reviews->count() }} отзывов</p>
            </div>
        </div>
    </div>

    {{-- Средняя колонка: Список отзывов --}}
    <div class="lg:col-span-2 space-y-4">
        <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 scrollbar-none-custom" id="reviews-list">
            @forelse($product->reviews as $review)
                <div class="border-b border-gray-100 pb-4 last:border-0">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-sm text-gray-800">{{ $review->user->name ?? $review->user->email }}</span>
                        <span class="text-xs text-gray-400">{{ $review->created_at->format('d.m.Y') }}</span>
                    </div>
                    <div class="flex items-center text-amber-400 my-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed mt-1">{{ $review->comment }}</p>

                    {{-- БЛОК ОТВЕТА АДМИНИСТРАЦИИ --}}
                    <div class="admin-reply-wrapper-{{ $review->id }}">
                        @if($review->admin_reply)
                            {{-- Если ответ уже есть — красиво выводим его в рамке --}}
                            <div class="mt-3 ml-6 p-4 bg-gray-50 border-l-4 border-orange-500 rounded-r-2xl animate__animated animate__fadeIn">
                                <div class="flex items-center justify-between mb-1">
                <span class="font-black text-[11px] text-orange-600 uppercase tracking-wider flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L1 17l1.338-3.123C1.493 12.767 1 11.434 1 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                    </svg>
                    Ответ администрации Paywin
                </span>
                                    <span class="text-[10px] text-gray-400">
                    {{ \Carbon\Carbon::parse($review->replied_at)->format('d.m.Y') }}
                </span>
                                </div>
                                <p class="text-sm text-gray-700 leading-relaxed">{{ $review->admin_reply }}</p>
                            </div>
                        @elseif(auth()->check() && auth()->user()->user_type === 'admin')
                            {{-- Если ответа нет, но зашел АДМИН — показываем микро-форму ответа --}}
                            <div class="mt-3 ml-6">
                                <form class="admin-reply-form flex gap-2 items-end" data-review-id="{{ $review->id }}">
                                    @csrf
                                    <div class="flex-1">
                                        <textarea name="admin_reply" rows="1" required placeholder="Написать ответ от администрации..." class="w-full text-xs rounded-xl border border-gray-200 p-2 focus:border-orange-400 focus:ring-1 focus:ring-orange-100 outline-none transition resize-none"></textarea>
                                    </div>
                                    <button type="submit" class="bg-orange-500 text-white text-[11px] font-bold px-4 py-2 rounded-xl hover:bg-orange-600 transition shadow-xs shrink-0 mb-0.5">
                                        Ответить
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                </div>
            @empty
                <p class="text-sm text-gray-400 py-4 italic" id="no-reviews-placeholder">Отзывов пока нет. Станьте первым!</p>
            @endforelse
        </div>

        {{-- Окно добавления отзыва --}}
        <div class="border-t border-gray-100 pt-4 mt-4">
            @auth
                <form id="review-form" class="space-y-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    {{-- Интерактивные звезды --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Ваша оценка:</label>
                        <div class="flex items-center gap-1 text-gray-300" id="star-rating-selector">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" data-value="{{ $i }}" class="star-btn transition-transform hover:scale-110 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating-input" value="">
                    </div>

                    {{-- Текст комментария --}}
                    <div>
                        <textarea name="comment" rows="3" required placeholder="Поделитесь впечатлениями о товаре..." class="w-full text-sm rounded-xl border border-gray-200 p-3 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 outline-none transition"></textarea>
                    </div>

                    <button type="submit" class="bg-gray-900 text-white text-xs font-bold px-5 py-2.5 rounded-xl hover:bg-black transition shadow-sm">
                        Отправить отзыв
                    </button>
                </form>
            @else
                <div class="bg-gray-50 p-4 rounded-xl text-center text-sm text-gray-500">
                    Чтобы оставить отзыв, пожалуйста, <a href="{{ route('login') }}" class="text-orange-500 font-bold underline">авторизуйтесь</a>.
                </div>
            @endauth
        </div>
    </div>
</div>

<script>
    // --- ИНТЕРАКТИВНЫЕ ЗВЕЗДЫ ---
    document.addEventListener('DOMContentLoaded', () => {
        const starContainer = document.getElementById('star-rating-selector');
        const ratingInput = document.getElementById('rating-input');

        if (starContainer && ratingInput) {
            const buttons = starContainer.querySelectorAll('.star-btn');

            buttons.forEach((btn, index) => {
                btn.addEventListener('click', () => {
                    const val = btn.getAttribute('data-value');
                    ratingInput.value = val;

                    // Подсвечиваем выбранное количество звезд
                    buttons.forEach((sBtn, i) => {
                        const svg = sBtn.querySelector('svg');
                        if (i <= index) {
                            sBtn.classList.remove('text-gray-300');
                            sBtn.classList.add('text-amber-400');
                            svg.setAttribute('fill', 'currentColor');
                        } else {
                            sBtn.classList.remove('text-amber-400');
                            sBtn.classList.add('text-gray-300');
                            svg.setAttribute('fill', 'none');
                        }
                    });
                });
            });
        }

        // --- ОТПРАВКА ФОРМЫ ОТЗЫВА ---
        const reviewForm = document.getElementById('review-form');
        if (reviewForm) {
            reviewForm.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!document.getElementById('rating-input').value) {
                    window.showToast("Пожалуйста, выберите оценку в звездах", "error");
                    return;
                }

                let formData = new FormData(this);

                fetch("{{ route('product.review.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            window.showToast(data.message);

                            // Удаляем плейсхолдер пустоты, если он был
                            const placeholder = document.getElementById('no-reviews-placeholder');
                            if(placeholder) placeholder.remove();

                            // Собираем HTML нового отзыва и добавляем его наверх списка
                            const reviewList = document.getElementById('reviews-list');
                            const rating = parseInt(document.getElementById('rating-input').value);

                            let starsHtml = '';
                            for(let i = 1; i <= 5; i++) {
                                starsHtml += `<svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 ${i <= rating ? 'fill-current' : 'text-gray-200'}" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>`;
                            }

                            const newReviewHtml = `
                        <div class="border-b border-gray-100 pb-4 last:border-0 animate__animated animate__fadeInDown">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-sm text-gray-800">${data.user_name}</span>
                                <span class="text-xs text-gray-400">${data.date}</span>
                            </div>
                            <div class="flex items-center text-amber-400 my-1">${starsHtml}</div>
                            <p class="text-sm text-gray-600 leading-relaxed mt-1">${reviewForm.querySelector('textarea').value}</p>
                        </div>
                    `;

                            reviewList.insertAdjacentHTML('afterbegin', newReviewHtml);

                            // Очищаем форму
                            reviewForm.reset();
                            // Сбрасываем подцветку звезд
                            buttons = starContainer.querySelectorAll('.star-btn');
                            buttons.forEach(sBtn => {
                                sBtn.classList.remove('text-amber-400');
                                sBtn.classList.add('text-gray-300');
                                sBtn.querySelector('svg').setAttribute('fill', 'none');
                            });
                            document.getElementById('rating-input').value = "";
                        } else {
                            window.showToast(data.message, "error");
                        }
                    })
                    .catch(error => {
                        window.showToast("Что-то пошло не так при отправке отзыва.", "error");
                    });
            });
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        // Делегирование событий для динамических форм ответов админа
        document.addEventListener('submit', function(e) {
            if (e.target && e.target.classList.contains('admin-reply-form')) {
                e.preventDefault();

                const form = e.target;
                const reviewId = form.getAttribute('data-review-id');
                const replyText = form.querySelector('textarea').value;
                let formData = new FormData(form);

                // Формируем URL динамически на основе ID отзыва
                const url = `/review/${reviewId}/reply`;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            window.showToast(data.message);

                            // Контейнер, куда мы поместим новый ответ
                            const container = document.querySelector(`.admin-reply-wrapper-${reviewId}`);

                            // Генерируем HTML-структуру ответа админа
                            const replyHtml = `
                        <div class="mt-3 ml-6 p-4 bg-gray-50 border-l-4 border-orange-500 rounded-r-2xl animate__animated animate__fadeIn">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-black text-[11px] text-orange-600 uppercase tracking-wider flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L1 17l1.338-3.123C1.493 12.767 1 11.434 1 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                                    </svg>
                                    Ответ администрации Paywin
                                </span>
                                <span class="text-[10px] text-gray-400">${data.date}</span>
                            </div>
                            <p class="text-sm text-gray-700 leading-relaxed">${replyText}</p>
                        </div>
                    `;

                            // Заменяем форму на готовый ответ
                            container.innerHTML = replyHtml;
                        } else {
                            window.showToast(data.message, "error");
                        }
                    })
                    .catch(error => {
                        window.showToast("Ошибка при отправке ответа.", "error");
                    });
            }
        });
    });
</script>
