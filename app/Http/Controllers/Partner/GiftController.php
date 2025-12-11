<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\PartnerGift;
use App\Models\PartnerGiftRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GiftController extends Controller
{
    public function index()
    {
        $partnerGifts = PartnerGift::where(['partner_id' => Auth::id()])
            ->selectRaw('partner_gifts.*, partner_gift_rules.min_order_total, partner_gift_rules.chance')
            ->join('partner_gift_rules', 'partner_gift_rules.partner_gift_id', '=', 'partner_gifts.id')
            ->get();
        return view('partner.gift.index', compact('partnerGifts'));
    }

    public function create()
    {
        return view('partner.gift.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $partnerGift = PartnerGift::create([
                'partner_id' => Auth::id(), 'title' => $data['title'], 'description' => $data['description'],
                'type' => $data['type'],
            ]);

            PartnerGiftRule::create([
                'partner_gift_id' => $partnerGift->id, 'min_order_total' => $data['min_order_total'], 'max_order_total' => $data['max_order_total'],
                'chance' => $data['chance'], 'max_per_user' => ($data['max_per_user'] == 0) ? null : $data['max_per_user'],
            ]);

            DB::commit();

            return redirect()->route('partner.gift.index');

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
