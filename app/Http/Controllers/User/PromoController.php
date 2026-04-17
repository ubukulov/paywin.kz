<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Promocode;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'share_id' => 'required|exists:shares,id',
            'code' => [
                'required',
                'string',
                'min:3',
                'max:25',
                'unique:promocodes,code', // Проверка на уникальность
                'regex:/^[A-Z0-9_-]+$/u', // Только буквы, цифры, тире
            ],
        ], [
            'code.unique' => 'Такой промокод уже занят, придумайте другой.',
            'code.regex' => 'Используйте только латинские буквы и цифры.'
        ]);

        $user = auth()->user();

        // Проверяем, нет ли уже кода для этой акции у этого агента
        $exists = Promocode::where('agent_id', $user->id)
            ->where('share_id', $request->share_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Вы уже создали промокод для этой акции.');
        }

        Promocode::create([
            'agent_id' => $user->id,
            'share_id' => $request->share_id,
            'code'     => strtoupper($request->code), // Приводим к верхнему регистру
        ]);

        return back()->with('success', 'Персональный промокод успешно создан!');
    }
}
