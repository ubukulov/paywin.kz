<?php

namespace App\Services;

use App\Models\UserGift;
use Illuminate\Support\Facades\Auth;

class UserPrizeService
{
    public function getMyPrizes()
    {
        return Auth::user()->gifts()
            ->with(['share.partner.partnerProfile', 'source'])
            ->latest()
            ->get();
    }
}
