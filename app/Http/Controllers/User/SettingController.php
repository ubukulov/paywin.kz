<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class SettingController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $user_profile = $user->profile;
        return view('user.profile', compact('user_profile'));
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();
        $user_profile = $user->profile;
        $user_profile->update($request->all());
        return redirect()->route('user.cabinet');
    }
}
