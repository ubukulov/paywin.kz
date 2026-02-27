<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.css">

<div class="mt-12 bg-slate-900 text-slate-400 p-8 md:p-12 rounded-t-[2.5rem]">
    <div class="max-w-5xl mx-auto flex flex-col md:flex-row justify-between items-start md:items-center gap-8">

        <div class="space-y-3">
            <p class="text-white font-bold text-sm mb-4">&copy; Paywin.kz 2024</p>

            <nav class="flex flex-col gap-2 text-xs font-medium">
                <a href="{{ route('aboutUs') }}" class="hover:text-white transition-colors">О нас</a>

                <a href="{{ asset('files/Политика конфиденциальности.docx') }}" target="_blank" class="hover:text-white transition-colors">
                    Политика конфиденциальности
                </a>

                <a href="{{ asset('files/Публичная оферта.docx') }}" target="_blank" class="hover:text-white transition-colors">
                    Публичная оферта
                </a>

                <a href="{{ asset('files/Политика возврата и обмена товаров.docx') }}" target="_blank" class="hover:text-white transition-colors">
                    Политика возврата и обмена товаров
                </a>

                <a href="{{ asset('files/Политика проведения онлайн-платежей.docx') }}" target="_blank" class="hover:text-white transition-colors">
                    Политика проведения онлайн-платежей
                </a>

                <a href="{{ asset('files/Юридическая информация.docx') }}" target="_blank" class="hover:text-white transition-colors">
                    Юридическая информация
                </a>
            </nav>
        </div>

        <div class="flex items-center gap-6 text-5xl text-white/20">
            <i class="fa fa-cc-visa hover:text-white/60 transition-colors cursor-help" title="Visa"></i>
            <i class="fa fa-cc-mastercard hover:text-white/60 transition-colors cursor-help" title="Mastercard"></i>
        </div>
    </div>

    <div class="max-w-5xl mx-auto mt-10 pt-6 border-t border-white/5 text-[10px] text-center text-slate-500 uppercase tracking-[0.2em]">
        Secure Payments & Data Protection
    </div>
</div>
