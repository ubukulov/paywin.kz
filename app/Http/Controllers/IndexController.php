<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Partner;
use App\Models\Category;
use App\Models\Payment;
use App\Models\Prize;
use App\Models\Share;
use App\Models\User;
use App\Models\UserBalance;
use App\Models\UserDiscount;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends BaseController
{
    public function home()
    {
        $categories = Category::all();
        return view('home',  compact('categories'));
    }

    public function prizes()
    {
        $shares = Share::where('cnt', '>', 0)
            ->with('user')
            ->get();

        $prizes = Prize::where(['user_id' => Auth::user()->id])
            ->with('user', 'share', 'payment')
            ->get();

        $winners = Prize::whereRaw('DATE_FORMAT(prizes.created_at, "%m") = '.date('m'))
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

        return view('prizes', compact('shares', 'prizes', 'winners', 'top_partners'));
    }

    public function paymentPage($slug, $id)
    {
        $partner = User::findOrFail($id);
        $user = User::findOrFail(Auth::user()->id);
        return view('payment', compact('slug', 'id', 'partner', 'user'));
    }

    public function aboutUs()
    {
        return view('about');
    }

    public function payment(Request $request)
    {
        $amount = $request->input('amount');
        $partner_id = $request->input('partner_id');
        $transaction_id = $request->input('transaction_id');

        $user = Auth::user();
        $partner = User::findOrFail($partner_id);
        $new_amount = $amount;


        $payment = Payment::create([
            'user_id' => $user->id, 'partner_id' => $partner_id, 'amount' => $amount, 'pg_status' => 'ok', 'pg_payment_id' => $transaction_id
        ]);

        $user->givePrize($partner->shares, $payment);

        return redirect()->route('payment.success');


        /*$payment = Payment::where(['user_id' => $user->id, 'partner_id' => $partner_id, 'amount' => $amount, 'pg_status' => 'waiting'])->first();
        if(!$payment) {

            if($discount) {
                $discount_amount = $user->getDiscountForUser();
                $need_amount = (int) $amount - ($amount * (int) $discount_amount) / 100;
            } else {
                $need_amount = 0;
            }

            $new_amount = ($need_amount > 0) ? $need_amount : $amount;

            if($balance) {
                $balance_amount = $user->getBalanceForUser();

                if($balance_amount >= $new_amount) {
                    $payment = Payment::create([
                        'user_id' => $user->id, 'partner_id' => $partner_id, 'amount' => $amount, 'pg_status' => 'ok'
                    ]);

                    if($discount) {
                        $discount_amount = $user->getDiscountForUser();
                        $user_discount = UserDiscount::where(['size' => $discount_amount, 'status' => 'active', 'partner_id' => $partner_id])->first();
                        if($user_discount) {
                            $user_discount->status = 'used';
                            $user_discount->payment_id = $payment->id;
                            $user_discount->save();
                        }
                    }

                    $user->payWithBalance($new_amount, $payment);

                    $user->givePrize($partner->shares, $payment);

                    return redirect()->route('payment.success');
                }

                if($balance_amount < $new_amount) {
                    $payment = Payment::create([
                        'user_id' => $user->id, 'partner_id' => $partner_id, 'amount' => $amount, 'pg_status' => 'waiting'
                    ]);
                    $user->reservationBalance($balance_amount, $payment);
                    $new_amount = $new_amount - $balance_amount;
                }
            } else {
                $payment = new Payment();
                $payment->user_id = $user->id;
                $payment->partner_id = $partner_id;
                $payment->amount = $amount;
                $payment->pg_status = 'waiting';
                $payment->save();
            }

            if($discount) {
                $discount_amount = $user->getDiscountForUser();
                $user_discount = UserDiscount::where(['size' => $discount_amount, 'status' => 'active', 'partner_id' => $partner_id])->first();
                if($user_discount) {
                    $user_discount->status = 'waiting';
                    $user_discount->payment_id = $payment->id;
                    $user_discount->save();
                }
            }
        }*/
    }

    public function paymentSuccess()
    {
        $user = Auth::user();
        /*$payment = Payment::where(['user_id' => $user->id, 'pg_status' => 'waiting'])->orderBy('id', 'DESC')->first();
        if($payment) {
            $request = [
                'pg_merchant_id'=> env('PAYBOX_MERCHANT_ID'),
                'pg_order_id' => $payment->id,
                'pg_salt' => env('PAYBOX_MERCHANT_SECRET'),
            ];

            //generate a signature and add it to the array
            ksort($request); //sort alphabetically
            array_unshift($request, 'get_status2.php');
            array_push($request, env('PAYBOX_MERCHANT_SECRET')); //add your secret key (you can take it in your personal cabinet on paybox system)

            $request['pg_sig'] = md5(implode(';', $request)); // signature

            unset($request[0], $request[1]);

            $apiUrl = env('PAYBOX_URL') . "get_status2.php";
            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', $apiUrl, [
                'headers' => [
                    'Content-type' => 'application/x-www-form-urlencoded'
                ],
                'form_params' => $request
            ]);
            $response = $response->getBody()->getContents();
            $responseXml = simplexml_load_string($response);

            $partner = $payment->partner;

            if((string)$responseXml->pg_status == 'ok') {
                $payment->pg_status = 'ok';
                $payment->pg_payment_id = (int) $responseXml->pg_payment_id;
                $payment->updated_at = Carbon::now();
                $payment->save();

                $user_balance = UserBalance::where(['user_id' => $user->id, 'status' => 'waiting', 'payment_id' => $payment->id])->get();
                if($user_balance) {
                    foreach($user_balance as $item) {
                        $item->status = 'withdraw';
                        $item->save();
                    }
                }

                $user_discount = UserDiscount::where(['partner_id' => $partner->id, 'status' => 'waiting', 'payment_id' => $payment->id])->first();
                if($user_discount) {
                    $user_discount->status = 'used';
                    $user_discount->save();
                }
            }


            if (!User::isPrize($user->id, $payment->id)) {
                $user->givePrize($partner->shares, $payment);
            }
        } else {
            $payment = Payment::where(['user_id' => $user->id, 'pg_status' => 'ok'])->orderBy('id', 'DESC')->first();
        }*/

        $payment = Payment::where(['user_id' => $user->id, 'pg_status' => 'ok'])->orderBy('id', 'DESC')->first();

        $prize = $payment->prize;
        if($prize) {
            $share = Share::findOrFail($prize->share_id);
        } else {
            $share = null;
        }


        return view('thanks', compact('payment', 'prize', 'share'));
    }

    public function paymentError(Request $request)
    {
        $payment_id = $request->input('pg_order_id');
        $payment = Payment::findOrFail($payment_id);
        $payment->pg_status = 'error';
        $payment->save();

        dd("Ошибка во время оплаты", $request->all());
    }

    public function review()
    {
        return view('review');
    }

    public function notGivenPrize($id)
    {
        $partner = User::findOrFail($id);
        return view('not_given_prize');
    }

    public function howItWorks()
    {
        return view('how_it_works');
    }
}
