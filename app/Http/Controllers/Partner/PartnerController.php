<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Payment;
use App\Models\UserAddress;
use App\Models\UserImage;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Auth;

class PartnerController extends Controller
{
    public function cabinet()
    {
        $partner = Auth::user();
        $user_profile = Auth::user()->profile;
        return view('partner.home', compact('user_profile', 'partner'));
    }

    public function qr()
    {
        $user_profile = Auth::user()->profile;
        return view('partner.qr', compact('user_profile'));
    }

    public function clients()
    {
        $payments = Payment::where(['pg_status' => 'ok'])
                    ->selectRaw('payments.*, user_profile.full_name')
                    ->join('users', 'users.id', 'payments.user_id')
                    ->join('user_profile', 'user_profile.user_id', 'users.id')
                    ->get();
        $users = [];
        foreach($payments as $payment) {
            if(array_key_exists($payment->user_id, $users)) {
                $users[$payment->user_id]['cnt'] += 1;
                $users[$payment->user_id]['sum'] += $payment->amount;
            } else {
                $users[$payment->user_id]['full_name'] = $payment->full_name;
                $users[$payment->user_id]['cnt'] = 1;
                $users[$payment->user_id]['sum'] = $payment->amount;
            }
        }

        return view('partner.clients', compact('users'));
    }

    public function edit()
    {
        $user_profile = Auth::user()->profile;
        $categories = Category::all();
        return view('partner.edit', compact('user_profile', 'categories'));
    }

    public function profileUpdate(Request $request)
    {
        $data = $request->all();
        $user_profile = UserProfile::where(['user_id' => $data['user_id']])->first();
        if ($user_profile) {

            if ($request->hasFile('logo')) {
                if(!empty($user_profile->logo) && file_exists(public_path() . $user_profile->logo)) {
                    unlink(public_path() . $user_profile->logo);
                }

                $request->validate([
                    'logo' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
                ]);

                $file = $request->file('logo');
                $ext = $file->getClientOriginalExtension();
                $name = md5(time()) . '.' . $ext;
                $path = '/upload/partners/';
                $dir = public_path() . $path;
                $file->move($dir, $name);
                $data['logo'] = $path.$name;
            }

            if ($request->hasFile('agreement')) {
                if(!empty($user_profile->agreement) && file_exists(public_path() . $user_profile->agreement)) {
                    unlink(public_path() . $user_profile->agreement);
                }

                $file = $request->file('agreement');
                $ext = $file->getClientOriginalExtension();
                $name = md5(time()) . '.' . $ext;
                $path = '/upload/partners/';
                $dir = public_path() . $path;
                $file->move($dir, $name);
                $data['agreement'] = $path.$name;
            }

            $user_profile->update($data);

            return redirect()->route('partner.cabinet');
        }
    }

    public function addressCreate()
    {
        return view('partner.address.create');
    }

    public function addressStore(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        UserAddress::create($data);
        return redirect()->route('partner.cabinet');
    }

    public function imageCreate()
    {
        return view('partner.image');
    }

    public function imageStore(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();
        foreach($data['images'] as $img) {
            $ext = $img->getClientOriginalExtension();
            $name = md5(time()) . '.' . $ext;
            $path = '/upload/partners/images/';
            $dir = public_path() . $path;
            $img->move($dir, $name);
            $data['user_id'] = $user->id;
            $data['image'] = $path.$name;
            UserImage::create($data);
        }

        return redirect()->route('partner.cabinet');
    }

    public function imageLists()
    {
        $images = UserImage::where(['user_id' => Auth::user()->id])->get();
        return view('partner.images', compact('images'));
    }

    public function imageDelete($id)
    {
        $image = UserImage::findOrFail($id);
        $image->delete();
        return redirect()->route('partner.cabinet');
    }
}
