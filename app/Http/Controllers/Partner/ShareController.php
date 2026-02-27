<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Share;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shares = Share::where(['user_id' => Auth::id()])
            ->whereDate('to_date', '>=', date('Y-m-d'))
            ->get();

        $shares_old = Share::where(['user_id' => Auth::id()])
            ->whereDate('to_date', '<', date('Y-m-d'))
            ->get();

        return view('partner.shares.index', compact('shares', 'shares_old'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('partner.shares.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        // Форматирование дат для БД
        $data['from_date'] = date('Y-m-d H:i:s', strtotime($data['from_date']));
        $data['to_date'] = date('Y-m-d H:i:s', strtotime($data['to_date']));

        if ($data['type'] == 'promocode') {
            // Устанавливаем тип промокода (discount, money, gift)
            $data['promo'] = $data['bonus_type'];
            $data['title'] = mb_strtoupper($data['title']);

            // Лимит активаций (в колонку cnt)
            $data['cnt'] = (int)$data['usage_limit'];

            // Процент для агента
            $data['agent_percent'] = (float)($data['agent_percent'] ?? 0);

            // Распределение значений по колонкам таблицы shares
            if ($data['bonus_type'] == 'gift') {
                $data['gift_title'] = $data['gift_description']; // Новое поле
                $data['size'] = 0;
                $data['from_order'] = 0;
            } elseif ($data['bonus_type'] == 'money') {
                $data['size'] = (int)$data['size']; // Сумма в ₸
                $data['from_order'] = 0;
            } else {
                // Тип: discount (скидка)
                $data['size'] = (int)$data['size']; // % скидки
                $data['from_order'] = (int)($data['min_sum'] ?? 0); // Порог активации
            }
        } else {
            $data['promo'] = 'none'; //
        }

        // Сохранение записи
        Share::create($data);

        return redirect()->route('partner.my-shares.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $share = Share::findOrFail($id);
        return view('partner.shares.edit', compact('share'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $share = Share::findOrFail($id);
        $share->update($request->all());
        return redirect()->route('partner.my-shares.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
