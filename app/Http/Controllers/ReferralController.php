<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;

class ReferralController extends Controller
{
    public function handle(string $code): RedirectResponse
    {
        $code = strtoupper(trim($code));

        // /ref/12
        if (is_numeric($code)) {
            if (!User::where('id', $code)->exists()) {
                abort(404);
            }
        }
        // /ref/AGENT12
        else {
            if (!Referral::where('promo_code', $code)->exists()) {
                abort(404);
            }
        }

        session([
            'ref_code' => $code,
            'ref_set_at' => now(),
        ]);

        Cookie::queue(
            Cookie::make('ref_code', $code, 60 * 24 * 30) // 30 дней
        );

        return redirect('/');
    }
}
