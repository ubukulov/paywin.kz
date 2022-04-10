<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Category;
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
        return view('partner.clients');
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
}
