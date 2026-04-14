<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;

class ReferralController extends Controller
{
    public function handleStep1($agent_id)
    {
        // Сохраняем только ID агента на 30 дней
        Cookie::queue('ref_agent_id', $agent_id, 60 * 24 * 30);
        Cookie::queue(Cookie::forget('ref_promo_code')); // Очищаем старый промокод, если был

        return redirect()->route('register');
    }

    public function handleStep2($agent_id, $promo_code)
    {
        // Сохраняем и ID агента, и промокод
        Cookie::queue('ref_agent_id', $agent_id, 60 * 24 * 30);
        Cookie::queue('ref_promo_code', strtoupper($promo_code), 60 * 24 * 30);

        return redirect()->route('register');
    }

    /**
     * НОВОЕ: Переход по ID агента сразу на страницу товара
     */
    public function handleProductLink($agent_id, $slug): RedirectResponse
    {
        // Запоминаем агента
        Cookie::queue('ref_agent_id', $agent_id, 60 * 24 * 30);
        // Очищаем промокод, так как это простой переход без кода
        Cookie::queue(Cookie::forget('ref_promo_code'));

        // Редирект на роут показа товара (убедись, что имя роута верное)
        return redirect()->route('product.show', $slug);
    }
}
