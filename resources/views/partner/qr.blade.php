@extends('partner.partner')

@section('content')
    @php
        use SimpleSoftwareIO\QrCode\Facades\QrCode;
    @endphp

    <div class="p-1 md:p-8 max-w-4xl mx-auto">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-2 md:p-10 flex flex-col items-center text-center">

                <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">
                    QR код Вашей компании
                </h2>
                <p class="text-gray-500 mb-8 max-w-md">
                    Используйте этот код для проведения акций, розыгрышей призов и привлечения новых клиентов.
                </p>

                <div class="bg-gray-50 p-6 rounded-2xl border-2 border-dashed border-gray-200 mb-10 transition-transform hover:scale-105 duration-300">
                    <div class="bg-white p-4 rounded-xl shadow-inner">
                        @php
                            $imgUrl = "/qrcodes/" . $partnerProfile->company . "_qr_code.svg";
                            $paymentUrl = route('paymentPage', ['slug' => $partnerProfile->category->slug, 'id' => $partnerProfile->partner_id]);
                        @endphp

                        {{-- Генерация для отображения --}}
                        {!! QrCode::size(200)->color(31, 41, 55)->margin(1)->generate($paymentUrl); !!}

                        {{-- Генерация файла (выполняется в фоне) --}}
                        @php
                            QrCode::size(500)->generate($paymentUrl, public_path() . $imgUrl);
                        @endphp
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">

                    <div class="flex flex-col items-center p-6 bg-blue-50 rounded-2xl border border-blue-100 group">
                        <a href="{{ asset($imgUrl) }}" target="_blank"
                           class="flex items-center gap-3 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold transition-all shadow-md active:scale-95 mb-3">
                            <img src="/images/cabinet/download.svg" alt="icon" class="w-5 h-5 brightness-0 invert">
                            скачать QR код
                        </a>
                        <p class="text-xs text-blue-600/70 text-center leading-relaxed">
                            Используйте QR для создания своего <br> маркетингового контента
                        </p>
                    </div>

                    <div class="flex flex-col items-center p-6 bg-orange-50 rounded-2xl border border-orange-100">
                        <a href="{{ asset('files/booklet.png') }}" target="_blank"
                           class="flex items-center gap-3 bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-xl font-bold transition-all shadow-md active:scale-95 mb-3">
                            <img src="/images/cabinet/booklet-icon.svg" alt="icon" class="w-5 h-5 brightness-0 invert">
                            скачать буклет
                        </a>
                        <p class="text-xs text-orange-600/70 text-center leading-relaxed">
                            Используйте наш готовый буклет <br> для запуска своей акции
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop
