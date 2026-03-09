<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\UserGift;
use App\Models\Share;
use App\Models\User;
use App\Models\UserBalance;
use App\Services\PromoService;
use App\Services\UserPrizeService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Referral;

class UserController extends Controller
{
    protected UserPrizeService $userPrizeService;

    public function __construct(UserPrizeService $userPrizeService){
        $this->userPrizeService = $userPrizeService;
    }

    public function cabinet()
    {
        $user = Auth::user();
        $user_profile = Auth::user()->profile;
        $prize = UserGift::where(['user_id' => $user->id, 'status' => 'waiting'])->first();
        return view('user.home', compact('user_profile', 'user', 'prize'));
    }

    public function addMyCard()
    {

    }

    public function removeMyCard()
    {

    }

    public function earn()
    {
        $promos = Share::actualPromocodes()->get();

        // Получаем список рефералов агента и оставляем по одной записи на каждую уникальную акцию
        $myPromos = Referral::where('agent_id', auth()->id())
            ->with('share')
            ->get()
            ->unique('share_id');

        return view('user.earn', compact('promos', 'myPromos'));
    }

    public function history(Request $request)
    {
        $user = auth()->user();

        $transactions = $user->transactions()
            ->latest()
            ->paginate(15, ['*'], 't_page'); // 't_page' чтобы пагинация не конфликтовала

        $orders = $user->orders()
            ->with(['items.product']) // Подгружаем товары в заказе
            ->latest()
            ->paginate(15, ['*'], 'o_page');

        return view('user.history', compact('transactions', 'orders'));
    }

    public function settings()
    {
        $user = Auth::user();
        $user_profile = $user->profile;
        return view('user.settings', compact('user_profile'));
    }

    public function balanceReplenishment(Request $request)
    {

    }

    public function promoActivate(Request $request, PromoService $promoService): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'promo_code' => 'required|string|max:50',
        ]);

        try {
            $promoService->activate(auth()->user(), $request->promo_code);

            return redirect()
                ->back()
                ->with('success', 'Промокод успешно активирован');

        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['error' => $e->getMessage()]);
        }
    }

    public function prizes()
    {
        /*$shares = Share::where('cnt', '>', 0)
            ->with('user')
            ->get();

        $prizes = UserGift::where(['user_id' => Auth::user()->id])
            ->with('user', 'share', 'payment')
            ->get();

        $winners = UserGift::whereRaw('DATE_FORMAT(prizes.created_at, "%m") = '.date('m'))
            //->with('user', 'share', 'payment')
            ->selectRaw('prizes.*, shares.user_id as partner_id, shares.title as share_title, shares.type as share_type, user_profile.full_name')
            ->join('shares', 'shares.id', 'prizes.share_id')
            ->join('user_profile', 'user_profile.user_id', 'prizes.user_id')
            ->where('prizes.status', '=', 'got')
            ->get();

        $ids = [];

        foreach($winners as $winner) {
            if(!in_array($winner->partner_id, $ids)) {
                $ids[] = $winner->partner_id;
            }
        }

        $top_partners = User::whereIn('id', $ids)->with('profile')->get();

        return view('user.prizes', compact('shares', 'prizes', 'winners', 'top_partners'));*/

        return view('user.prizes', [
            'prizes' => $this->userPrizeService->getMyPrizes()
        ]);
    }
}
