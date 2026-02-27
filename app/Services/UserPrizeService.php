<?php

namespace App\Services;

use App\Models\Prize;
use Illuminate\Support\Facades\Auth;

class UserPrizeService
{
    public function getMyPrizes()
    {
        return Prize::where(['user_id' => Auth::user()->id])
            ->with('user', 'share', 'payment')
            ->get();
    }
}
