<?php

namespace App\Http\Controllers\Partner;

use App\Enums\ShareType;
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
        $shares = Share::where(['partner_id' => Auth::id()])
            ->whereDate('to_date', '>=', date('Y-m-d'))
            ->get();

        $shares_old = Share::where(['partner_id' => Auth::id()])
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
        $data['partner_id'] = Auth::user()->id;
        // Форматирование дат для БД
        $data['from_date'] = date('Y-m-d H:i:s', strtotime($data['from_date']));
        $data['to_date'] = date('Y-m-d H:i:s', strtotime($data['to_date']));

        if ($data['type'] == ShareType::GIFT->value) {
            $extraData = [
                'count' => $request->count,
                'from_order' => $request->from_order,
                'to_order' => $request->to_order,
                'c_winning' => $request->c_winning,
            ];
        } elseif ($data['type'] == ShareType::DISCOUNT->value) {
            $extraData = [
                'count' => $request->count,
                'size' => $request->size,
                'from_order' => $request->from_order,
                'to_order' => $request->to_order,
                'c_winning' => $request->c_winning,
                'max_sum' => $request->max_sum,
            ];
        } elseif ($data['type'] == ShareType::CASHBACK->value) {
            $extraData = [
                'count' => $request->count,
                'size' => $request->size,
                'from_order' => $request->from_order,
                'to_order' => $request->to_order,
                'c_winning' => $request->c_winning,
            ];
        } else {
            $request->validate([
                'title' => [
                    'required',
                    'string',
                    'max:20',
                    'regex:/^[a-zA-Z0-9]+$/u', // Только латиница и цифры, без пробелов
                ],
            ], [
                'title.regex' => 'Название должно содержать только латинские буквы и цифры без пробелов.'
            ]);
            $data['code'] = $request->title;
            $extraData['agent_percent'] = $request->agent_percent;
            $extraData['usage_limit'] = $request->usage_limit;

            if ($request->bonus_type == ShareType::DISCOUNT->value) {
                $extraData['bonus_type'] = ShareType::DISCOUNT->value;
                $extraData['min_sum'] = $request->min_sum;
                $extraData['size'] = $request->size;
            } elseif ($request->bonus_type == 'money') {
                $extraData['bonus_type'] = 'money';
                $extraData['size'] = $request->size;
            } else {
                $extraData['bonus_type'] = 'gift';
                $extraData['gift_name'] = $request->gift_name;
            }
        }

        $data['data'] = $extraData;

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
