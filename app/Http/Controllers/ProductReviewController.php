<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'required|string|min:5|max:1000',
        ], [
            'comment.min' => 'Отзыв слишком короткий (минимум 5 символов).',
            'comment.required' => 'Напишите текст отзыва.',
            'rating.required' => 'Пожалуйста, выберите оценку.'
        ]);

        $review = ProductReview::create([
            'user_id'    => Auth::id(),
            'product_id' => $request->product_id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Спасибо! Ваш отзыв успешно добавлен.',
            'user_name' => Auth::user()->name ?? Auth::user()->email,
            'date' => $review->created_at->format('d.m.Y')
        ]);
    }

    public function reply(Request $request, ProductReview $review)
    {
        // Проверяем, является ли пользователь админом (адаптируй под свою структуру)
        if (auth()->user()->user_type !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Доступ запрещен.'], 403);
        }

        $request->validate([
            'admin_reply' => 'required|string|min:2|max:1000',
        ], [
            'admin_reply.required' => 'Введите текст ответа.'
        ]);

        $review->update([
            'admin_reply' => $request->admin_reply,
            'replied_at'  => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ответ успешно опубликован.',
            'date' => $review->replied_at->format('d.m.Y')
        ]);
    }
}
